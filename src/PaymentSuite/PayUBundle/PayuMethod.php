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

namespace PaymentSuite\PayUBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PayuMethod
 */
class PayuMethod implements PaymentMethodInterface
{
    /**
     * Method name
     */
    const METHOD_NAME = 'Payu';

    /**
     * @var string
     *
     * orderId
     */
    protected $orderId;

    /**
     * @var string
     *
     * transactionId
     */
    protected $transactionId;

    /**
     * @var string
     *
     * state
     */
    protected $state;

    /**
     * @var string
     *
     * responseCode
     */
    protected $responseCode;

    /**
     * @var string
     *
     * paymentNetworkResponseCode
     */
    protected $paymentNetworkResponseCode;

    /**
     * @var string
     *
     * paymentNetworkResponseErrorMessage
     */
    protected $paymentNetworkResponseErrorMessage;

    /**
     * @var string
     *
     * trazabilityCode
     */
    protected $trazabilityCode;

    /**
     * @var string
     *
     * authorizationCode
     */
    protected $authorizationCode;

    /**
     * @var string
     *
     * responseMessage
     */
    protected $responseMessage;

    /**
     * @var string
     *
     * operationDate
     */
    protected $operationDate;

    /**
     * @var array
     *
     * extraParameters
     */
    protected $extraParameters;

    /**
     * Get Payu method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return self::METHOD_NAME;
    }

    /**
     * Sets State
     *
     * @param string $state State
     *
     * @return PayuMethod Self object
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
     * Sets AuthorizationCode
     *
     * @param string $authorizationCode AuthorizationCode
     *
     * @return PayuMethod Self object
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
     * Sets ExtraParameters
     *
     * @param array $extraParameters ExtraParameters
     *
     * @return PayuMethod Self object
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

    /**
     * Sets OperationDate
     *
     * @param string $operationDate OperationDate
     *
     * @return PayuMethod Self object
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
     * Sets PaymentNetworkResponseCode
     *
     * @param string $paymentNetworkResponseCode PaymentNetworkResponseCode
     *
     * @return PayuMethod Self object
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
     * @return PayuMethod Self object
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
     * @return PayuMethod Self object
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
     * @return PayuMethod Self object
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
     * Sets TrazabilityCode
     *
     * @param string $trazabilityCode TrazabilityCode
     *
     * @return PayuMethod Self object
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
     * Sets OrderId
     *
     * @param string $orderId OrderId
     *
     * @return PayuMethod Self object
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get OrderId
     *
     * @return string OrderId
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Sets TransactionId
     *
     * @param string $transactionId TransactionId
     *
     * @return PayuMethod Self object
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
}
