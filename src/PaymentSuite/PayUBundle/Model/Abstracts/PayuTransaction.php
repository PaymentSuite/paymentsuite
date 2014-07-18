<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Model\Abstracts;

/**
 * Abstract Model class for transaction models
 */
abstract class PayuTransaction
{
    /**
     * @var string
     *
     * type
     */
    protected $type;

    /**
     * Sets Type
     *
     * @param string $type Type
     *
     * @return PayuTransaction Self object
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Type
     *
     * @return string Type
     */
    public function getType()
    {
        return $this->type;
    }
}