<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymentCoreBundle\Tests\Sandbox;

use Mmoreram\PaymentCoreBundle\Services\Abstracts\AbstractPaymentExtraDataValidator;

/**
 * PaymentExtraDataValidator dummie class
 */
class PaymentExtraDataValidator extends AbstractPaymentExtraDataValidator
{

    /**
     * Return extra data fields needed for this bundle
     */
    public function getFields()
    {
        return array(

            'customer_field1',
            'customer_field2',
            'order_field3',
        );
    }
}