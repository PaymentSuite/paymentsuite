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
use PaymentSuite\PayuBundle\Model\Abstracts\PayuRequest;
use PaymentSuite\PayuBundle\Model\PaymentResponse;

/**
 * PayuManager
 */
class PayuManager
{
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
     * @return PaymentResponse Payu Response
     */
    public function processPaymentRequest(PayuRequest $request)
    {
        $response = $this->processRequest($request, $this->paymentServer, 'PaymentSuite\PayuBundle\Model\PaymentResponse');

        die(var_dump($response));
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
        //$jsonResponse = '{"code":"SUCCESS","error":null,"transactionResponse":{"orderId":3230260,"transactionId":"1fe526f7-6c42-422b-92b2-0d8e3e43cc72","state":"ERROR","paymentNetworkResponseCode":null,"paymentNetworkResponseErrorMessage":null,"trazabilityCode":null,"authorizationCode":null,"pendingReason":null,"responseCode":"ENTITY_DECLINED","errorCode":"ENTITY_NO_RESPONSE","responseMessage":null,"transactionDate":null,"transactionTime":null,"operationDate":null,"extraParameters":null}}';
        //$jsonResponse = '{"code":"SUCCESS","error":null,"transactionResponse":{"orderId":38549360,"transactionId":"7c384b37-507d-4c7b-8a49-4f74c2e8ef7c","state":"PENDING","paymentNetworkResponseCode":null,"paymentNetworkResponseErrorMessage":null,"trazabilityCode":"0500560091711403310831171620","authorizationCode":null,"pendingReason":"AWAITING_NOTIFICATION","responseCode":"PENDING_TRANSACTION_CONFIRMATION","errorCode":null,"responseMessage":null,"transactionDate":null,"transactionTime":null,"operationDate":1396272692675,"extraParameters":{"VISANET_PE_URL":"https://www.multimerchantvisanet.com/formularioweb/formulariopago.asp"}}}';
        $response = $this->serializer->deserialize($jsonResponse, $responseClass, 'json');

        return $response;
    }
}
