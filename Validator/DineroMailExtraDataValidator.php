<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package DineromailBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\DineromailBundle\Validator;

use Mmoreram\PaymentCoreBundle\Services\Abstracts\AbstractPaymentExtraDataValidator;

/**
 * DineroMailExtraDataValidator class
 */
class DineroMailExtraDataValidator extends AbstractPaymentExtraDataValidator
{

    /**
     * Return extra data fields needed for this bundle
     * 
     * @return array Fields to validate
     */
    public function getFields()
    {
        return array(

            'customer_firstname',
            'customer_lastname',
            'customer_email',
            'customer_phone',
            'language',
        );
    }
}