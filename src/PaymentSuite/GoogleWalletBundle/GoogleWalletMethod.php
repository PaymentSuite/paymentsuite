<?php

/**
 * GoogleWalletBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package GoogleWalletBundle
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\GoogleWalletBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * GoogleWalletMethod class
 */
class GoogleWalletMethod implements PaymentMethodInterface
{

    /**
     * Get Google Wallet method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Google Wallet';
    }

    /**
     * @var string
     *
     * Google Wallet response api token
     */
    private $apiToken;

    /**
     * @var integer
     *
     * Google Wallet transaction id
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
     * set Api token
     *
     * @param string $apiToken Api token
     *
     * @return GoogleWalletMethod self Object
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
     * @return GoogleWalletMethod self Object
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
     * @return GoogleWalletMethod self Object
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
     * @return GoogleWalletMethod self Object
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
