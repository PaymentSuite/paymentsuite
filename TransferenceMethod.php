<?php

/**
 * TransferenceBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package TransferenceBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\TransferenceBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


/**
 * TransferenceMethod class
 */
class TransferenceMethod implements PaymentMethodInterface
{

    /**
     * Get Transference method name
     * 
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Transference';
    }
}