<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * TransactionResponse Model
 */
class TransactionResponse
{
    /**
     * @var integer
     * @JMS\Type("string")
     *
     * orderId
     */
    protected $orderId;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * transactionId
     */
    protected $transactionId;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * state
     */
    protected $state;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * responseCode
     */
    protected $responseCode;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * paymentNetworkResponseCode
     */
    protected $paymentNetworkResponseCode;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * paymentNetworkResponseErrorMessage
     */
    protected $paymentNetworkResponseErrorMessage;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * trazabilityCode
     */
    protected $trazabilityCode;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * authorizationCode
     */
    protected $authorizationCode;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * responseMessage
     */
    protected $responseMessage;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * operationDate
     */
    protected $operationDate;

    /**
     * @var array
     * @JMS\Type("array")
     *
     * extraParameters
     */
    protected $extraParameters;

    /**
     * Sets AuthorizationCode
     *
     * @param string $authorizationCode AuthorizationCode
     *
     * @return TransactionResponse Self object
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * Get AuthorizationCode
     *
     * @return string AuthorizationCode
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * Sets OperationDate
     *
     * @param string $operationDate OperationDate
     *
     * @return TransactionResponse Self object
     */
    public function setOperationDate($operationDate)
    {
        $this->operationDate = $operationDate;

        return $this;
    }

    /**
     * Get OperationDate
     *
     * @return string OperationDate
     */
    public function getOperationDate()
    {
        return $this->operationDate;
    }

    /**
     * Sets OrderId
     *
     * @param int $orderId OrderId
     *
     * @return TransactionResponse Self object
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get OrderId
     *
     * @return int OrderId
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Sets PaymentNetworkResponseCode
     *
     * @param string $paymentNetworkResponseCode PaymentNetworkResponseCode
     *
     * @return TransactionResponse Self object
     */
    public function setPaymentNetworkResponseCode($paymentNetworkResponseCode)
    {
        $this->paymentNetworkResponseCode = $paymentNetworkResponseCode;

        return $this;
    }

    /**
     * Get PaymentNetworkResponseCode
     *
     * @return string PaymentNetworkResponseCode
     */
    public function getPaymentNetworkResponseCode()
    {
        return $this->paymentNetworkResponseCode;
    }

    /**
     * Sets PaymentNetworkResponseErrorMessage
     *
     * @param string $paymentNetworkResponseErrorMessage PaymentNetworkResponseErrorMessage
     *
     * @return TransactionResponse Self object
     */
    public function setPaymentNetworkResponseErrorMessage($paymentNetworkResponseErrorMessage)
    {
        $this->paymentNetworkResponseErrorMessage = $paymentNetworkResponseErrorMessage;

        return $this;
    }

    /**
     * Get PaymentNetworkResponseErrorMessage
     *
     * @return string PaymentNetworkResponseErrorMessage
     */
    public function getPaymentNetworkResponseErrorMessage()
    {
        return $this->paymentNetworkResponseErrorMessage;
    }

    /**
     * Sets ResponseCode
     *
     * @param string $responseCode ResponseCode
     *
     * @return TransactionResponse Self object
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * Get ResponseCode
     *
     * @return string ResponseCode
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Sets ResponseMessage
     *
     * @param string $responseMessage ResponseMessage
     *
     * @return TransactionResponse Self object
     */
    public function setResponseMessage($responseMessage)
    {
        $this->responseMessage = $responseMessage;

        return $this;
    }

    /**
     * Get ResponseMessage
     *
     * @return string ResponseMessage
     */
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * Sets State
     *
     * @param string $state State
     *
     * @return TransactionResponse Self object
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get State
     *
     * @return string State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets TransactionId
     *
     * @param string $transactionId TransactionId
     *
     * @return TransactionResponse Self object
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get TransactionId
     *
     * @return string TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets TrazabilityCode
     *
     * @param string $trazabilityCode TrazabilityCode
     *
     * @return TransactionResponse Self object
     */
    public function setTrazabilityCode($trazabilityCode)
    {
        $this->trazabilityCode = $trazabilityCode;

        return $this;
    }

    /**
     * Get TrazabilityCode
     *
     * @return string TrazabilityCode
     */
    public function getTrazabilityCode()
    {
        return $this->trazabilityCode;
    }

    /**
     * Sets ExtraParameters
     *
     * @param array $extraParameters ExtraParameters
     *
     * @return TransactionResponse Self object
     */
    public function setExtraParameters($extraParameters)
    {
        $this->extraParameters = $extraParameters;

        return $this;
    }

    /**
     * Get ExtraParameters
     *
     * @return array ExtraParameters
     */
    public function getExtraParameters()
    {
        return $this->extraParameters;
    }
}
