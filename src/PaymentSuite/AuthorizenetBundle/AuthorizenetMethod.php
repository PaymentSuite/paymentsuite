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

namespace PaymentSuite\AuthorizenetBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * AuthorizenetMethod class
 */
class AuthorizenetMethod implements PaymentMethodInterface
{
    /**
     * Get Authorizenet method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Authorizenet';
    }

    /**
     * @var string
     *
     * Authorizenet response api token
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
     * @var integer
     *
     * Authorizenet transaction id
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
     * set Credit card number
     *
     * @param string $creditCardNumber Credit card number
     *
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
     * @return AuthorizenetMethod self Object
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
