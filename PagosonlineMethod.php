<?php

namespace Scastells\PagosonlineBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


/**
 * PaymillMethod class
 */
class PagosonlineMethod implements PaymentMethodInterface
{

    /**
     * Get Pagosonline method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Pagosonline';
    }


    /**
     * @var float
     *
     * Pagosonline amount
     */
    private $amount;


    /**
     * set amount
     *
     * @param float $amount Amount
     *
     * @return PagosonlineMethod self Object
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