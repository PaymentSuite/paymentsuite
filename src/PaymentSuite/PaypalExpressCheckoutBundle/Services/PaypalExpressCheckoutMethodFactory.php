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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Services;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\PaypalExpressCheckoutBundle\PaypalExpressCheckoutMethod;

/**
 * Class PaypalExpressCheckoutMethodFactory
 */
class PaypalExpressCheckoutMethodFactory
{
    /**
     * Create new PaymentMethodInterface instance
     *
     * @param float  $amount        Amount
     * @param string $orderNumber   Order Number
     * @param array  $someExtraData Some extra data
     *
     * @return PaymentMethodInterface New instance
     */
    public function create(
        $amount,
        $orderNumber,
        array $someExtraData
    ) {
        return PaypalExpressCheckoutMethod::create(
            $amount,
            $orderNumber,
            $someExtraData
        );
    }
}
