<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PayUBundle\Services;

use JMS\Serializer\Serializer;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;
use PaymentSuite\PayuBundle\Factory\PayuDetailsFactory;
use PaymentSuite\PayuBundle\Factory\PayuRequestFactory;
use PaymentSuite\PayuBundle\Model\Abstracts\PayuRequest;
use PaymentSuite\PayuBundle\Model\PaymentResponse;
use PaymentSuite\PayuBundle\Model\TransactionResponse;
use PaymentSuite\PayuBundle\Model\TransactionResponseDetailDetails;
use PaymentSuite\PayuBundle\Model\TransactionResponseDetailPayload;
use PaymentSuite\PayuBundle\Model\TransactionResponseDetailRequest;
use PaymentSuite\PayuBundle\Model\TransactionResponseDetailResponse;
use PaymentSuite\PayuBundle\PayuDetailsTypes;
use PaymentSuite\PayuBundle\PayuMethod;
use PaymentSuite\PayuBundle\PayuRequestTypes;

/**
 * PayuManager
 */
class PayuManager
{
    /**
     * Payu notification success code
     */
    const PAYU_NOTIF_SUCCESS = '4';

    /**
     * Payu notification validating code
     */
    const PAYU_NOTIF_VALIDATING = '7';

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
     * @var PayuRequestFactory
     *
     * requestFactory
     */
    protected $requestFactory;

    /**
     * @var PayuDetailsFactory
     *
     * detailsFactory
     */
    protected $detailsFactory;

    /**
     * @var PaymentEventDispatcher
     *
     * paymentEventDispatcher
     */
    protected $paymentEventDispatcher;

    /**
     * @var PaymentBridgeInterface
     *
     * paymentBridge
     */
    protected $paymentBridge;
    /**
     * @var \PaymentSuite\PaymentCoreBundle\Services\PaymentLogger
     *
     * paymentLogger
     */
    private $paymentLogger;

    /**
     * Construct method
     *
     * @param boolean                $useStage               Use Payu stage servers
     * @param string                 $merchantKey            Merchant Key
     * @param string                 $merchantId             Merchant Id
     * @param Serializer             $serializer             Serializer
     * @param PayuRequestFactory     $requestFactory         Request Factory
     * @param PayuDetailsFactory     $detailsFactory         Details Factory
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param PaymentLogger          $paymentLogger          Payment Logger
     */
    public function __construct($useStage, $merchantKey, $merchantId, Serializer $serializer,
                                PayuRequestFactory $requestFactory, PayuDetailsFactory $detailsFactory,
                                PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge,
                                PaymentLogger $paymentLogger)
    {
        if ($useStage) {
            $this->paymentServer = $this::PAYU_PAYMENT_STAGE_SERVER;
            $this->reportServer = $this::PAYU_REPORT_STAGE_SERVER;
        } else {
            $this->paymentServer = $this::PAYU_PAYMENT_SERVER;
            $this->reportServer = $this::PAYU_REPORT_SERVER;
        }
        $this->useStage = $useStage;
        $this->merchantKey = $merchantKey;
        $this->merchantId = $merchantId;
        $this->serializer = $serializer;
        $this->requestFactory = $requestFactory;
        $this->detailsFactory = $detailsFactory;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->paymentLogger = $paymentLogger;
        $this->paymentLogger->setPaymentBundle(PayuMethod::METHOD_NAME);
    }

    /**
     * Process Payment Request
     *
     * @param PayuRequest $request Payu Request
     *
     * @throws PaymentException
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
     * Process Payment Request
     *
     * @param TransactionResponseDetailRequest $request TransactionResponseDetail Request
     *
     * @throws PaymentException
     *
     * @return TransactionResponseDetailPayload TransactionResponseDetail Payload
     */
    public function processTransactionResponseDetailRequest(TransactionResponseDetailRequest $request)
    {
        /** @var TransactionResponseDetailResponse $response */
        $response = $this->processRequest($request, $this->reportServer, 'PaymentSuite\PayuBundle\Model\TransactionResponseDetailResponse');

        if ($response->getCode() != self::PAYU_CODE_SUCCESS) {
            throw new PaymentException($response->getError());
        }

        return $response->getResult()->getPayload();
    }

    /**
     * Calculate signature
     *
     * @param string $reference Order reference
     * @param string $amount    Order amount
     * @param string $currency  Order currency
     *
     * @return string Transaction signature
     */
    public function getSignature($reference, $amount, $currency)
    {
        $signature = md5($this->merchantKey.'~'.$this->merchantId.'~'.$reference.'~'.$amount.'~'.$currency);

        return $signature;
    }

    /**
     * Check transaction status
     *
     * @param string $transactionId Payu transaction ID
     */
    public function checkTransactionStatus($transactionId)
    {
        /** @var TransactionResponseDetailDetails $details */
        $details = $this->detailsFactory->create(PayuDetailsTypes::TYPE_TRANSACTION_RESPONSE_DETAIL);
        $details->setTransactionId($transactionId);
        /** @var $request TransactionResponseDetailRequest */
        $request = $this->requestFactory->create(PayuRequestTypes::TYPE_TRANSACTION_RESPONSE_DETAIL);
        $request->setDetails($details);

        try {
            $payload = $this->processTransactionResponseDetailRequest($request);
            $paymentMethod = new PayuMethod();
            $paymentMethod
                ->setTransactionId($transactionId)
                ->setState($payload->getState())
                ->setResponseMessage($payload->getResponseMessage())
                ->setResponseCode($payload->getResponseCode())
                ->setPaymentNetworkResponseErrorMessage($payload->getPaymentNetworkResponseErrorMessage())
                ->setAuthorizationCode($payload->getAuthorizationCode())
                ->setExtraParameters($payload->getExtraParameters())
                ->setOperationDate($payload->getOperationDate())
                ->setPaymentNetworkResponseCode($payload->getPaymentNetworkResponseCode())
                ->setTrazabilityCode($payload->getTrazabilityCode());

            switch ($payload->getState()) {
                case 'APPROVED':
                    $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);
                    $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);
                    break;
                case 'PENDING':
                    break;
                default:
                    $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);
                    $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
                    break;
            }
        } catch (PaymentException $e) {
        }
    }

    /**
     * Process Notification Request from PayU
     *
     * @param string $transactionId Transaction ID
     * @param string $state         Transaction state
     *
     * @throws \PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException
     */
    public function processNotification($transactionId, $state)
    {
        $paymentEventDispatcher = $this->paymentEventDispatcher;
        $paymentBridge = $this->paymentBridge;
        $paymentMethod = new PayuMethod();
        $paymentMethod->setTransactionId($transactionId);

        $paymentEventDispatcher->notifyPaymentOrderLoad($paymentBridge, $paymentMethod);

        if (!$paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        switch ($state) {
            case self::PAYU_NOTIF_SUCCESS:
                $paymentEventDispatcher->notifyPaymentOrderSuccess($paymentBridge, $paymentMethod);
                break;
            case self::PAYU_NOTIF_VALIDATING:
                break;
            default:
                $paymentEventDispatcher->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
                break;
        }
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
        $this->paymentLogger->log('Request: '.$jsonData);

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

        $this->paymentLogger->log('Response: '.$jsonResponse);
        $response = $this->serializer->deserialize($jsonResponse, $responseClass, 'json');

        return $response;
    }
}
