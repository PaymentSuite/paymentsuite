<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Factory;

use PaymentSuite\PayuBundle\Model\AdditionalValue;

/**
 * Class AdditionalValueFactory
 */
class AdditionalValueFactory
{
    /**
     * Creates an instance of AdditionalValue model
     *
     * @return AdditionalValue Empty model
     */
    public function create()
    {
        $additionalValue = new AdditionalValue();

        return $additionalValue;
    }
}
