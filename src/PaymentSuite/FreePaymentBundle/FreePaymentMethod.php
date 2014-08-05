<?php

/**
 * FreePaymentBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\FreePaymentBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * FreePaymentMethod class
 */
class FreePaymentMethod implements PaymentMethodInterface
{
    /**
     * @var string
     *
     * method name
     */
    const PAYMENT_METHOD_NAME = 'FreePayment';

    /**
     * Get Free payment method name
     *
     * @return string Free payment name
     */
    public function getPaymentName()
    {
        return self::PAYMENT_METHOD_NAME;
    }
}
