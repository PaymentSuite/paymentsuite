<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\GestpayBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * GestpayMethod class.
 */
final class GestpayMethod implements PaymentMethodInterface
{
    /**
     * Get Free payment method name.
     *
     * @return string Payment name
     */
    const METHOD_NAME = 'gestpay';

    private $transactionResult;
    private $shopTransactionId;
    private $bankTransactionId;
    private $authorizationCode;
    private $currency;
    private $amount;
    private $errorCode;
    private $errorDescription;

    public function getPaymentName()
    {
        return self::METHOD_NAME;
    }

    /**
     * @param mixed $transactionResult
     *
     * @return GestpayMethod
     */
    public function setTransactionResult($transactionResult)
    {
        $this->transactionResult = $transactionResult;

        return $this;
    }

    /**
     * @param mixed $shopTransactionId
     *
     * @return GestpayMethod
     */
    public function setShopTransactionId($shopTransactionId)
    {
        $this->shopTransactionId = $shopTransactionId;

        return $this;
    }

    /**
     * @param mixed $bankTransactionId
     *
     * @return GestpayMethod
     */
    public function setBankTransactionId($bankTransactionId)
    {
        $this->bankTransactionId = $bankTransactionId;

        return $this;
    }

    /**
     * @param mixed $authorizationCode
     *
     * @return GestpayMethod
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * @param mixed $currency
     *
     * @return GestpayMethod
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param mixed $amount
     *
     * @return GestpayMethod
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param mixed $errorCode
     *
     * @return GestpayMethod
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @param mixed $errorDescription
     *
     * @return GestpayMethod
     */
    public function setErrorDescription($errorDescription)
    {
        $this->errorDescription = $errorDescription;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionResult()
    {
        return $this->transactionResult;
    }

    /**
     * @return mixed
     */
    public function getShopTransactionId()
    {
        return $this->shopTransactionId;
    }

    /**
     * @return mixed
     */
    public function getBankTransactionId()
    {
        return $this->bankTransactionId;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrorDescription()
    {
        return $this->errorDescription;
    }
}
