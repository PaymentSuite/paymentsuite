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

namespace PaymentSuite\DineromailBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;


/**
 * DineromailMethod class
 */
class DineromailMethod implements PaymentMethodInterface
{

    /**
     * Get Dineromail method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Dineromail';
    }


    /**
     * @var float
     *
     * Dineromail amount
     */
    private $amount;


    /**
     * @var string
     *
     * dineromail transactionid
     */
    private $dineromailTransactionId;


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


    /**
     * @param string $dineromailTransactionId
     *
     * @return $this
     */
    public function setDineromailTransactionId($dineromailTransactionId)
    {
        $this->dineromailTransactionId = $dineromailTransactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDineromailTransactionId()
    {
        return $this->dineromailTransactionId;
    }

}