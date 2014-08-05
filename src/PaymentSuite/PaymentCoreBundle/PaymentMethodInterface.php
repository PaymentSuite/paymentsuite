<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaymentCoreBundle;

/**
 * Interface for all type of payments
 */
interface PaymentMethodInterface
{
    /**
     * Return type of payment name
     *
     * @return string
     */
    public function getPaymentName();
}
