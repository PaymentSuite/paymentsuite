<?php

namespace PaymentSuite\SafetypayBundle\Services;

use PaymentSuite\SafetypayBundle\SafetypayBundle;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\SafetypayBundle\SafetypayMethod;
use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;

/**
 * SafetyPay Manager
 *
 */
class SafetypayManager
{
    /**
     * @var time
     *
     * Date in UNIX Timestamp
     */
    private $requestDateTime;

    /**
     * @var string
     *
     * format response
     */
    private $responseFormat;

    /**
     * @var string
     *
     * url to connect safetypay sandbox/live set in parameters
     */
    private $urlGetTokenExpress;

    /**
     * @var string
     *
     * signature key
     */
     private $signatureKey;

    /**
     * @var PaymentBridge
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */

    protected $eventDispatcher;

    /**
     * @var string
     *
     * key
     */
    private $key;

     /**
     * @var PaymentLogger
     *
     * paymentLogger
     */
    private $paymentLogger;

    /**
     * @param string                 $responseFormat
     * @param string                 $urlGetTokenExpress
     * @param string                 $signatureKey
     * @param PaymentBridgeInterface $paymentBridge
     * @param PaymentEventDispatcher $eventDispatcher
     * @param String                 $key
     * @param PaymentLogger          $paymentLogger
     */
    public function __construct($responseFormat = 'XML',
                                $urlGetTokenExpress,
                                $signatureKey,
                                PaymentBridgeInterface $paymentBridge,
                                PaymentEventDispatcher $eventDispatcher,
                                $key,
                                PaymentLogger $paymentLogger)
    {
        $this->responseFormat = $responseFormat;
        $this->urlGetTokenExpress = $urlGetTokenExpress;
        $this->signatureKey = $signatureKey;
        $this->requestDateTime = $this->getDateIso8601(time());
        $this->paymentBridge = $paymentBridge;
        $this->eventDispatcher = $eventDispatcher;
        $this->key = $key;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * @param $aData
     * @param  string $pListByConcat
     * @param  bool   $pOtherRequestDateTime
     * @return mixed
     */
    public function getSignature($aData, $pListByConcat = '', $pOtherRequestDateTime = false)
    {
        $stringToConcat = '';
        foreach (explode(',', $pListByConcat) as $key => $value) {
            $stringToConcat .= $aData[rtrim(ltrim($value))];
        }

        return hash('sha256', ($pOtherRequestDateTime? '' : $this->requestDateTime).$stringToConcat.$this->signatureKey);
    }

    /**
     * Function use for safetyPay module
     * @param $int_date
     * @return bool|string
     */
    public function getDateIso8601($int_date)
    {
        $date_mod       = date('Y-m-d\TH:i:s', $int_date);
        $pre_timezone   = date('O', $int_date);
        $time_zone      = substr($pre_timezone, 0, 3) . ':' . substr($pre_timezone, 3, 2);
        $pos            = strpos($time_zone, "-");

        if ($pos === false) {   // nothing
        } else
            if ($pos != 0)
                $date_mod = $time_zone;
            else
                if (is_string($pos) && !$pos) {   // nothing
                } else
                    if ($pos != 0)
                        $date_mod = $time_zone;

        return $date_mod;
    }

    public function getUrlToken($_data, $html_link = true)
    {
        $data = array();
        $responseFormat = '';
        while (list($n,$v) = each($_data)) {
            if ($n == 'ResponseFormat') {
                $responseFormat = urlencode($v);
            }
            $data[] = "$n=" . urlencode($v);
        }
        $data = implode('&', $data);
        $url = parse_url($this->urlGetTokenExpress);
        $host = $url['host'];
        $path = $url['path'];

        $try_number = 1;
        $connection_success = false;

        while ($try_number <= 3) {

            $fp = fsockopen(($url['scheme'] == 'https'? 'ssl://'.$host:$host), ($url['scheme'] == 'https'? 443:80), $errno, $errstr);    // open a socket

            if ($fp) {
                fputs($fp, "POST $path HTTP/1.1\r\n");
                fputs($fp, "Host: $host\r\n");
                fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-length: ". strlen($data) ."\r\n");
                fputs($fp, "Connection: close\r\n\r\n");
                fputs($fp, $data);

                $result = '';
                while(!feof($fp))
                    $result .= fgets($fp, 128);

                fclose($fp);
                $result = explode("\r\n\r\n", $result, 2);
                $try_number = 1000;
                $connection_success = true;
            } else {
                //log error
                $try_number++;
                sleep(2);
            }

        }

        if (!$connection_success) {
            return 'Error';
        }

        // Response Format as XML
        if ($responseFormat == 'XML') {
            $search = "/<ErrorNumber(?:.*?)>(.*)<\/ErrorNumber>/U";
            preg_match_all($search, $result[1], $match_error);

            if ((int) $match_error[1][0] != 0) {
                $search = "/<Description(?:.*?)>(.*)<\/Description>/U";
                preg_match_all($search, $result[1], $match_errordesc);

                return '<span style="color:red;">Error: ' . '(' . $match_error[1][0] . ') ' . $match_errordesc[1][0] . '</span>';// return error message
            } else {
                $search = "/<ClientRedirectURL(?:.*?)>(.*)<\/ClientRedirectURL>/U";
                preg_match_all($search, $result[1], $match);

                return (($html_link)? '<a href="' . $match[1][0] . '" target="_blank">SafetyPay</a>': $match[1][0]);// return Token URL EXPRESS
            }
        } else
            // Response Format as CSV
        {
            $match = explode(',', $result[1], 4);
            if ($match[0] == '0')
                return (($html_link)? '<a href="' . $match[2] . '" target="_blank">SafetyPay</a>': $match[2]);// return Token URL EXPRESS
            else
                return '<span style="color:red;">Error: ' . ($match[0] == '1'?'1, Invalid credentials':'2, Merchant has not sent data') .'</span>';	// return error message
        }
    }

    /**
     * @return string
     */
    public function getresponseFormat()
    {
        return $this->responseFormat;
    }

    /**
     * @return time
     */
    public function getRequestDateTime()
    {
        return $this->requestDateTime;
    }

    /**
     * Post url update the order status
     *
     * @param SafetypayMethod $paymentMethod
     * @param Array           $postData
     */
    public function confirmPayment(SafetypayMethod $paymentMethod, $postData)
    {
        $this->paymentLogger->setPaymentBundle($paymentMethod->getPaymentName());
        $jsonData = json_encode($postData);
        $this->paymentLogger->log('Response: ' .$jsonData);
        $paymentMethod->setReference($postData['MerchantReferenceNo']);
        $paymentMethod->setRequestDateTime($postData['RequestDateTime']);
        $paymentMethod->setSignature($postData['Signature']);

        $paymentBridge = $this->paymentBridge;
        $signature = $this->getSignature($postData, 'RequestDateTime, MerchantReferenceNo', true);
        if ($postData['ApiKey'] !== '' || $postData['Signature'] !== '') {

            if ($this->key == $postData['ApiKey']) {
                if ($postData['Signature'] == $signature) {
                    $this->eventDispatcher->notifyPaymentOrderLoad($paymentBridge, $paymentMethod);
                    $this->eventDispatcher->notifyPaymentOrderSuccess($paymentBridge, $paymentMethod);
                }
            }
        }

    }
}
