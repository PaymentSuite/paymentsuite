<?php

namespace Mmoreram\DineromailBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\DineromailBundle\DineromailMethod;

/**
 * Dineromail manager
 */
class DineromailManager
{

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;


    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;


    /**
     * @var array
     *
     * Dineromail subdomains
     */
    private $subdomain = array(
        1 => 'argentina',
        2 => 'brazil',
        3 => 'chile',
        4 => 'mexico');


    /**
     * @var integer
     *
     * Country ID
     */
    private $countryId;


    /**
     * @var string
     *
     * Merchant ID
     */
    private $merchantId;


    /**
     * @var string
     *
     * Merchant password
     */
    private $merchantPwd;


    /**
     * @var logger
     *
     */
    private $logger;


    /**
     * Construct method for dineromail manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param $countryId
     * @param $merchantId
     * @param $merchantPwd
     * @param $logger
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $countryId, $merchantId, $merchantPwd, $logger)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->countryId = $countryId;
        $this->merchantId = $merchantId;
        $this->merchantPwd = $merchantPwd;
        $this->logger = $logger;
    }


    /**
     * Checks transaction status
     *
     * @param string         $transactionId        Transaction Id
     *
     * @return SimpleXMLElement     TransactionId details if successfull query, null otherwise
     */
    public function queryTransaction($transactionId)
    {
        $url = 'http://'.$this->subdomain[$this->countryId].'.dineromail.com/Vender/Consulta_IPN.asp';
        $data = 'DATA=<REPORTE><NROCTA>'.$this->merchantId.'</NROCTA><DETALLE><CONSULTA><CLAVE>'.
            $this->merchantPwd.'</CLAVE><TIPO>1</TIPO><OPERACIONES><ID>'.$transactionId.
            '</ID></OPERACIONES></CONSULTA></DETALLE></REPORTE>';
        $parsedUrl = parse_url($url);

        if ($fp = fsockopen($parsedUrl['host'], 80))
        {
            fwrite($fp, "POST {$parsedUrl['path']} HTTP/1.1\r\n");
            fwrite($fp, "Host: {$parsedUrl['host']}\r\n");
            fwrite($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fwrite($fp, 'Content-length: '.strlen($data)."\r\n");
            fwrite($fp, "Connection: close\r\n\r\n");
            fwrite($fp, $data);
            $result = '';
            while (!feof($fp))
                $result .= fgets($fp);
            fclose($fp);
            $result = explode("\r\n\r\n", $result, 2);
            $header = isset($result[0]) ? $result[0] : '';
            $content = isset($result[1]) ? str_replace("&", "", $result[1]) : '';
            $xmlStart = strpos($content, '<?');
            $xml = new \SimpleXMLElement(substr($content, $xmlStart, strrpos($content, '>') - $xmlStart + 1));
            if ($oper = $xml->DETALLE->OPERACIONES->OPERACION) {
                return $oper;
            }
        }

        return null;
    }


    /**
     * Given a Dineromail response, as a SimpleXMLElement, perform desired operations
     *
     * @param   SimpleXMLElement    $xml            Dineromail response
     * @return  DineromailManager   Self object
     */
    public function processTransaction($xml)
    {
        $paymentMethod = new DineromailMethod();

        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransaction: '.$xml->asXML());

        switch ($xml->ESTADO)
        {
            case 1: // Pending, nothing to do
                break;
            case 2: // Accepted
                $paymentMethod->setDineromailTransactionId($xml->ID);
                $paymentMethod->setAmount($xml->MONTO);
                $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);
                $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);
                break;
            case 3: // Denied
                $paymentMethod->setDineromailTransactionId($xml->ID);
                $paymentMethod->setAmount($xml->MONTO);
                $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);
                $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
                break;
            default:
                break;
        }

        return $this;
    }
}