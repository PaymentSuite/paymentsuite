<?php

/**
 * BeFactory PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Mmoreram 2013
 */

namespace Mmoreram\PaymillBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


/**
 * PaymillMethod class
 */
class PaymillMethod implements PaymentMethodInterface
{

    /**
     * @inherit
     */
    public function getPaymentName()
    {
        return 'Paymill';
    }


    /**
     * @var float
     *
     * Paymill amount
     */
    private $amount;


    /**
     * @var string
     *
     * Credit Cart number
     */
    private $creditCartNumber;


    /**
     * @var string
     *
     * Credit cart owner
     */
    private $creditCartOwner;


    /**
     * @var integer
     *
     * Credit cart expiration year
     */
    private $creditCartExpirationYear;


    /**
     * @var integer
     *
     * Credit cart expiration month value
     */
    private $creditCartExpirationMonth;


    /**
     * @var string
     *
     * Credit cart security value
     */
    private $creditCartSecurity;


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
     * set amount
     *
     * @param float $amount Amount
     *
     * @return PaymillMethod self Object
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }


    /**
     * Get amount
     *
     * @return float amount
     */
    public function getAmount()
    {
        return $this->amount;
    }


    /**
     * set Credit cart number
     *
     * @param string $creditCartNumber Credit cart number
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCartNumber($creditCartNumber)
    {
        $this->creditCartNumber = $creditCartNumber;

        return $this;
    }


    /**
     * Get Credit cart number
     *
     * @return string Credit cart number
     */
    public function getCreditCartNumber()
    {
        return $this->creditCartNumber;
    }


    /**
     * set Credit cart owner
     *
     * @param string $creditCartOwner Credit cart owner
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCartOwner($creditCartOwner)
    {
        $this->creditCartOwner = $creditCartOwner;

        return $this;
    }


    /**
     * Get Credit cart owner
     *
     * @return string Credit cart owner
     */
    public function getCreditCartOwner()
    {
        return $this->creditCartOwner;
    }


    /**
     * set Credit cart expiration year
     *
     * @param integer $creditCartExpirationYear Credit cart expiration year
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCartExpirationYear($creditCartExpirationYear)
    {
        $this->creditCartExpirationYear = $creditCartExpirationYear;

        return $this;
    }


    /**
     * Get Credit cart expiration year
     *
     * @return integer Credit cart expiration year
     */
    public function getCreditCartExpirationYear()
    {
        return $this->creditCartExpirationYear;
    }


    /**
     * set Credit cart expiration month
     *
     * @param $creditCartExpirationMonth
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCartExpirationMonth($creditCartExpirationMonth)
    {
        $this->creditCartExpirationMonth = $creditCartExpirationMonth;

        return $this;
    }


    /**
     * Get Credit cart expiration month
     *
     * @return integer Credit cart expiration month
     */
    public function getCreditCartExpirationMonth()
    {
        return $this->creditCartExpirationMonth;
    }


    /**
     * set Credit cart security
     *
     * @param string $creditCartSecurity Credit cart security
     *
     * @return PaymillMethod self Object
     */
    public function setCreditCartSecurity($creditCartSecurity)
    {
        $this->creditCartSecurity = $creditCartSecurity;

        return $this;
    }


    /**
     * Get Credit cart security
     *
     * @return float Credit cart security
     */
    public function getCreditCartSecurity()
    {
        return $this->creditCartSecurity;
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

}