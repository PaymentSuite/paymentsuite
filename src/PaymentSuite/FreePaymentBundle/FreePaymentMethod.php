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

namespace PaymentSuite\FreePaymentBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * FreePaymentMethod class
 */
class FreePaymentMethod implements PaymentMethodInterface
{
    /**
     * @var string
     *
     * method name
     */
    const PAYMENT_METHOD_NAME = 'FreePayment';

    /**
     * Get Free payment method name
     *
     * @return string Free payment name
     */
    public function getPaymentName()
    {
        return self::PAYMENT_METHOD_NAME;
    }
}
