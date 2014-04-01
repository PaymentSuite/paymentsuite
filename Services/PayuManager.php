<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Services;

use JMS\Serializer\Serializer;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PayuBundle\Model\Abstracts\PayuRequest;
use PaymentSuite\PayuBundle\Model\PaymentResponse;
use PaymentSuite\PayuBundle\Model\TransactionResponse;

/**
 * PayuManager
 */
class PayuManager
{
    /**
     * Payu Response success code
     */
    const PAYU_CODE_SUCCESS = 'SUCCESS';

    /**
     * Payu Response error code
     */
    const PAYU_CODE_ERROR = 'ERROR';

    /**
     * Payu Payment server
     */
    const PAYU_PAYMENT_SERVER = 'https://api.payulatam.com/payments-api/4.0/service.cgi';

    /**
     * Payu Payment stage server
     */
    const PAYU_PAYMENT_STAGE_SERVER = 'https://stg.api.payulatam.com/payments-api/4.0/service.cgi';

    /**
     * Payu Report server
     */
    const PAYU_REPORT_SERVER = 'https://api.payulatam.com/reports-api/4.0/service.cgi';

    /**
     * Payu Report stage server
     */
    const PAYU_REPORT_STAGE_SERVER = 'https://stg.api.payulatam.com/reports-api/4.0/service.cgi';

    /**
     * @var string
     *
     * paymentServer
     */
    protected $paymentServer;

    /**
     * @var string
     *
     * reportServer
     */
    protected $reportServer;

    /**
     * @var string
     *
     * merchantKey
     */
    protected $merchantKey;

    /**
     * @var string
     *
     * merchantId
     */
    protected $merchantId;

    /**
     * @var Serializer
     *
     * serializer
     */
    protected $serializer;

    /**
     * Construct method
     *
     * @param boolean    $useStage    Use Payu stage servers
     * @param string     $merchantKey Merchant Key
     * @param string     $merchantId  Merchant Id
     * @param Serializer $serializer  Serializer
     */
    public function __construct($useStage, $merchantKey, $merchantId, Serializer $serializer)
    {
        if ($useStage) {
            $this->paymentServer = $this::PAYU_PAYMENT_STAGE_SERVER;
            $this->reportServer = $this::PAYU_REPORT_STAGE_SERVER;
        } else {
            $this->paymentServer = $this::PAYU_PAYMENT_SERVER;
            $this->reportServer = $this::PAYU_REPORT_SERVER;
        }
        $this->useStage = $useStage;
        $this->serializer = $serializer;
        $this->merchantKey = $merchantKey;
        $this->merchantId = $merchantId;
    }

    /**
     * Process Payment Request
     *
     * @param PayuRequest $request Payu Request
     *
     * @return TransactionResponse Payu Response
     */
    public function processPaymentRequest(PayuRequest $request)
    {
        /** @var PaymentResponse $response */
        $response = $this->processRequest($request, $this->paymentServer, 'PaymentSuite\PayuBundle\Model\PaymentResponse');

        if ($response->getCode() != self::PAYU_CODE_SUCCESS) {
            throw new PaymentException($response->getError());
        }

        return $response->getTransactionResponse();
    }

    /**
     * Calculate signature
     *
     * @return string Transaction signature
     */
    public function getSignature($reference, $amount, $currency)
    {
        $signature = md5($this->merchantKey.'~'.$this->merchantId.'~'.$reference.'~'.$amount.'~'.$currency);

        return $signature;
    }

    /**
     * Process Payu Request
     *
     * @param PayuRequest $request       Payu Request
     * @param string      $host          Payu host
     * @param string      $responseClass Payu Response Class
     *
     * @return string Raw Payu Response
     */
    protected function processRequest(PayuRequest $request, $host, $responseClass)
    {
        $jsonData = $this->serializer->serialize($request, 'json');

        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "Content-Length: ".strlen($jsonData)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $jsonResponse = curl_exec($ch);
        $response = $this->serializer->deserialize($jsonResponse, $responseClass, 'json');

        return $response;
    }
}
