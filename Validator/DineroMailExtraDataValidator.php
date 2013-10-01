<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymillBundle\Validator;

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