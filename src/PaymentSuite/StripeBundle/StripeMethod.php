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

namespace PaymentSuite\StripeBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use ArrayAccess;

/**
 * StripeMethod class.
 */
final class StripeMethod implements PaymentMethodInterface
{
    /**
     * Get Stripe method name.
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Stripe';
    }

    /**
     * @var string
     *
     * Stripe response api token
     */
    private $apiToken;

    /**
     * @var string
     *
     * Credit Card number
     */
    private $creditCardNumber;

    /**
     * @var string
     *
     * Credit card owner
     */
    private $creditCardOwner;

    /**
     * @var int
     *
     * Credit card expiration year
     */
    private $creditCardExpirationYear;

    /**
     * @var int
     *
     * Credit card expiration month value
     */
    private $creditCardExpirationMonth;

    /**
     * @var string
     *
     * Credit card security value
     */
    private $creditCardSecurity;

    /**
     * @var int
     *
     * Stripe transaction id
     */
    private $transactionId;

    /**
     * @var ArrayAccess
     *
     * Transaction response data
     */
    private $transactionResponse;

    /**
     * @var string
     *
     * Transaction status
     */
    private $transactionStatus;

    /**
     * Construct method.
     *
     * @param string $apiToken                  Api token
     * @param string $creditCardNumber          Credit card number
     * @param string $creditCardOwner           Credit card owner
     * @param string $creditCardExpirationYear  Credit card expiration year
     * @param string $creditCardExpirationMonth Credit card expiration month
     * @param string $creditCardSecurity        Credit card security
     */
    public function __construct(
        $apiToken,
        $creditCardNumber,
        $creditCardOwner,
        $creditCardExpirationYear,
        $creditCardExpirationMonth,
        $creditCardSecurity
    ) {
        $this->apiToken = $apiToken;
        $this->creditCardNumber = $creditCardNumber;
        $this->creditCardOwner = $creditCardOwner;
        $this->creditCardExpirationYear = $creditCardExpirationYear;
        $this->creditCardExpirationMonth = $creditCardExpirationMonth;
        $this->creditCardSecurity = $creditCardSecurity;
    }

    /**
     * Get ApiToken.
     *
     * @return string ApiToken
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Get CreditCardNumber.
     *
     * @return string CreditCardNumber
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }

    /**
     * Get CreditCardOwner.
     *
     * @return string CreditCardOwner
     */
    public function getCreditCardOwner()
    {
        return $this->creditCardOwner;
    }

    /**
     * Get CreditCardExpirationYear.
     *
     * @return int CreditCardExpirationYear
     */
    public function getCreditCardExpirationYear()
    {
        return $this->creditCardExpirationYear;
    }

    /**
     * Get CreditCardExpirationMonth.
     *
     * @return int CreditCardExpirationMonth
     */
    public function getCreditCardExpirationMonth()
    {
        return $this->creditCardExpirationMonth;
    }

    /**
     * Get CreditCardSecurity.
     *
     * @return string CreditCardSecurity
     */
    public function getCreditCardSecurity()
    {
        return $this->creditCardSecurity;
    }

    /**
     * Get TransactionId.
     *
     * @return int TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets TransactionId.
     *
     * @param int $transactionId TransactionId
     *
     * @return $this Self object
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get TransactionResponse.
     *
     * @return ArrayAccess TransactionResponse
     */
    public function getTransactionResponse()
    {
        return $this->transactionResponse;
    }

    /**
     * Sets TransactionResponse.
     *
     * @param ArrayAccess $transactionResponse TransactionResponse
     *
     * @return $this Self object
     */
    public function setTransactionResponse($transactionResponse)
    {
        $this->transactionResponse = $transactionResponse;

        return $this;
    }

    /**
     * Get TransactionStatus.
     *
     * @return string TransactionStatus
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * Sets TransactionStatus.
     *
     * @param string $transactionStatus TransactionStatus
     *
     * @return $this Self object
     */
    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }
}
