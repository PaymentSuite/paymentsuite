<?php

namespace Scastells\SafetypayBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;

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