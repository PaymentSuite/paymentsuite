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

namespace PaymentSuite\FreePaymentBundle\Services;

use PaymentSuite\FreePaymentBundle\FreePaymentMethod;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class FreePaymentMethodFactory
 */
class FreePaymentMethodFactory
{
    /**
     * Create new PaymentMethodInterface instance
     *
     * @return PaymentMethodInterface New instance
     */
    public function create()
    {
        return new FreePaymentMethod();
    }
}
