<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
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
 * FreePaymentMethod class.
 */
final class FreePaymentMethod implements PaymentMethodInterface
{
    /**
     * Get Free payment method name.
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'free_payment';
    }
}
