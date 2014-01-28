<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <canis.viridi@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2013
 */

namespace PaymentSuite\RedsysBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;



/**
 * RedsysMethod class
 */
class RedsysMethod implements PaymentMethodInterface
{

    /**
     * Get Redsys method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Redsys';
    }

}