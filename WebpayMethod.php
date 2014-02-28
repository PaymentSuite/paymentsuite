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
     * @var float
     *
     * amount
     */
    protected $amount;

    /**
     * @var string
     *
     * order reference
     */
    protected $reference;

    /**
     * Get Webpay method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Webpay';
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
     * Set reference
     *
     * @param string $reference Reference
     *
     * @return WebpayMethod self Object
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }
}
