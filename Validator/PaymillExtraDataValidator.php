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
 * PaymillExtraDataValidator class
 */
class PaymillExtraDataValidator extends AbstractPaymentExtraDataValidator
{

    /**
     * Return extra data fields needed for this bundle
     * 
     * @return array Fields to validate
     */
    public function getFields()
    {
        return array(

            'order_description',
        );
    }
}