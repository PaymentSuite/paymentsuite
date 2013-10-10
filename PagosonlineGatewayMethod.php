<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package DineromailBundle
 *
 * Marc Morera 2013
 */

namespace Scastells\PagosonlineGatewayBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


/**
 * DineromailMethod class
 */
class PagosonlineGatewayMethod implements PaymentMethodInterface
{

    /**
     * Get Dineromail method name
     * 
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'PagosonlineGateway';
    }


    /**
     * @var float
     *
     * Dineromail amount
     */
    private $amount;


    /**
     * set amount
     *
     * @param float $amount Amount
     *
     * @return DineromailMethod self Object
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