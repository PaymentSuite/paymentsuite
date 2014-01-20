<?php

/**
 * BankwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BankwireBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\BankwireBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;


/**
 * BankwireMethod class
 */
class BankwireMethod implements PaymentMethodInterface
{

    /**
     * Get Bankwire method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Bankwire';
    }
}