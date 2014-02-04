<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 *
 */

namespace Scastells\PayuBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;


/**
 * PayuMethod class
 */
class PayuMethod implements PaymentMethodInterface
{
    /**
     * Get BanwireMethod method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Payu';
    }


    /**
     * @var float
     *
     * Banwire amount
     */
    private $amount;


    /*
   * @var string
   *
   * Credit card type
   */
    private $cardType;


    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $cardType
     * @return $this
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }
}