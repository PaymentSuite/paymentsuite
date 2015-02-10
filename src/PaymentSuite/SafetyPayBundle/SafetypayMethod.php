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

namespace PaymentSuite\SafetyPayBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

class SafetypayMethod implements PaymentMethodInterface
{
    /**
     * Get Safetypay method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'SafetyPay';
    }

    /**
     * @var float
     *
     * Safetypay amount
     */
    private $amount;

    /**
     * @var string
     *
     * Safetypay reference
     */
    private $reference;

    /**
     * @var mixed
     */
    private $requestDateTime;

    /**
     * @var string
     *
     * Safetypay signature
     */
    private $signature;

    /**
     * @param string $requestDateTime
     *
     * @return $this
     */
    public function setRequestDateTime($requestDateTime)
    {
        $this->requestDateTime = $requestDateTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestDateTime()
    {
        return $this->requestDateTime;
    }

    /**
     * @param string $signature
     *
     * @return $this
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * set amount
     *
     * @param float $amount Amount
     *
     * @return SafetypayMethod self Object
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

}
