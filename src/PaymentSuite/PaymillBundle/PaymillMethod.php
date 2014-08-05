<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaymillBundle;

use Paymill\Models\Response\Transaction;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PaymillMethod class
 */
class PaymillMethod implements PaymentMethodInterface
{
    /**
     * Get Paymill method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Paymill';
    }

    /**
     * @var string
     *
     * Currency
     */
    private $currency;

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
     * @var integer
     *
     * Credit card expiration year
     */
    private $creditCardExpirationYear;

    /**
     * @var integer
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
     * @var string
     *
     * Paymill response api token
     */
    private $apiToken;

    /**
     * @var integer
     *
     * Paymill transaction id
     */
    private $transactionId;

    /**
     * @var string
     *
     * Transaction status
     */
    private $transactionStatus;

    /**
     * @var Transaction
     *
     * Transaction
     */
    private $transaction;

    /**
     * set currency
     *
     * @param string $currency Currency
     *
     * @return PaymillMethod self Object
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * set Credit card number
     *
     * @param string $creditCardNumber Credit card number
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    /**
     * Get Credit card number
     *
     * @return string Credit card number
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }

    /**
     * set Credit card owner
     *
     * @param string $creditCardOwner Credit card owner
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCardOwner($creditCardOwner)
    {
        $this->creditCardOwner = $creditCardOwner;

        return $this;
    }

    /**
     * Get Credit card owner
     *
     * @return string Credit card owner
     */
    public function getCreditCardOwner()
    {
        return $this->creditCardOwner;
    }

    /**
     * set Credit card expiration year
     *
     * @param integer $creditCardExpirationYear Credit card expiration year
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCardExpirationYear($creditCardExpirationYear)
    {
        $this->creditCardExpirationYear = $creditCardExpirationYear;

        return $this;
    }

    /**
     * Get Credit card expiration year
     *
     * @return integer Credit card expiration year
     */
    public function getCreditCardExpirationYear()
    {
        return $this->creditCardExpirationYear;
    }

    /**
     * set Credit card expiration month
     *
     * @param integer $creditCardExpirationMonth Credit card expiration month
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCardExpirationMonth($creditCardExpirationMonth)
    {
        $this->creditCardExpirationMonth = $creditCardExpirationMonth;

        return $this;
    }

    /**
     * Get Credit card expiration month
     *
     * @return integer Credit card expiration month
     */
    public function getCreditCardExpirationMonth()
    {
        return $this->creditCardExpirationMonth;
    }

    /**
     * set Credit card security
     *
     * @param string $creditCardSecurity Credit card security
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCardSecurity($creditCardSecurity)
    {
        $this->creditCardSecurity = $creditCardSecurity;

        return $this;
    }

    /**
     * Get Credit card security
     *
     * @return float Credit card security
     */
    public function getCreditCardSecurity()
    {
        return $this->creditCardSecurity;
    }

    /**
     * set Api token
     *
     * @param string $apiToken Api token
     *
     * @return PaymillMethod self Object
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * Get Api token
     *
     * @return string Api token
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * set Transaction id
     *
     * @param integer $transactionId Transaction id
     *
     * @return PaymillMethod self Object
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get Transaction id
     *
     * @return integer Transaction id
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * set Transaction status
     *
     * @param string $transactionStatus Transaction status
     *
     * @return PaymillMethod self Object
     */
    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }

    /**
     * Get Transaction status
     *
     * @return string Transaction status
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * Set Transaction
     *
     * @param Paymill\Models\Response\Transaction $transaction Transaction
     *
     * @return PaymillMethod self Object
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return Paymill\Models\Response\Transaction Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

}
