<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model;

/**
 * AdditionalValue Model
 */
class AdditionalValue
{
    /**
     * @var float
     *
     * value
     */
    protected $value;

    /**
     * @var string
     *
     * currency
     */
    protected $currency;

    /**
     * Sets Currency
     *
     * @param string $currency Currency
     *
     * @return AdditionalValue Self object
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get Currency
     *
     * @return string Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets Value
     *
     * @param float $value Value
     *
     * @return AdditionalValue Self object
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get Value
     *
     * @return float Value
     */
    public function getValue()
    {
        return $this->value;
    }
}
