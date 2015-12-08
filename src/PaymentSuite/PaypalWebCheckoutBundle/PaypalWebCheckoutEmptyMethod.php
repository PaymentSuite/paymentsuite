<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaypalWebCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class PaypalWebCheckoutEmptyMethod.
 */
class PaypalWebCheckoutEmptyMethod implements PaymentMethodInterface
{
    /**
     * Return type of payment name.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return 'paypal_web_checkout';
    }
}
