<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * WebpayMethod
 */
class WebpayMethod implements PaymentMethodInterface
{
    /**
     * Webpay method name
     */
    const WEBPAY_METHOD_NAME = 'Webpay';

    /**
     * @var float
     *
     * amount
     */
    protected $amount;

    /**
     * @var string
     *
     * order transaction id
     */
    protected $transactionId;

    /**
     * @var string
     *
     * session id
     */
    protected $sessionId;

    /**
     * Get Webpay method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return $this::WEBPAY_METHOD_NAME;
    }

    /**
     * Set amount
     *
     * @param float $amount Amount
     *
     * @return WebpayMethod self Object
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
     * Set transaction id
     *
     * @param string $transactionId Transaction Id
     *
     * @return WebpayMethod self Object
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transaction id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
