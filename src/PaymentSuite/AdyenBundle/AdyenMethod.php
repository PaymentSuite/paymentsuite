<?php
/*
 * This file is part of the Mascoteros package.
 *
 * Copyright (c) 2015-2016 Mascoteros.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */
namespace PaymentSuite\AdyenBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;


class AdyenMethod implements PaymentMethodInterface
{
    /**
     * Get API method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Adyen API';
    }

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
     * @var integer
     *
     * transaction id
     */
    private $transactionId;

    /**
     * @var array
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
     * set Credit cart number
     *
     * @param string $creditCartNumber Credit cart number
     *
     * @return AdyenMethod self Object
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
     * @return AdyenMethod self Object
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
     * @return AdyenMethod self Object
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
     * @param integer $creditCartExpirationMonth Credit cart expiration month
     *
     * @return AdyenMethod self Object
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
     * @return AdyenMethod self Object
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
     * @return AdyenMethod self Object
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
     * set Transaction response
     *
     * @param string $transactionResponse Transaction response
     *
     * @return AdyenMethod self Object
     */
    public function setTransactionResponse($transactionResponse)
    {
        $this->transactionResponse = $transactionResponse;

        return $this;
    }

    /**
     * Get Transaction response
     *
     * @return string Transaction response
     */
    public function getTransactionResponse()
    {
        return $this->transactionResponse;
    }

    /**
     * set Transaction status
     *
     * @param string $transactionStatus Transaction status
     *
     * @return AdyenMethod self Object
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
