<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PayuMethod
 */
class PayuMethod implements PaymentMethodInterface
{
    /**
     * Method name
     */
    const METHOD_NAME = 'Payu';

    /**
     * Get Payu method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return $this::METHOD_NAME;
    }
}