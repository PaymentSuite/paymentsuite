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

namespace PaymentSuite\PaypalWebCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class PaypalWebCheckoutEmptyMethod.
 */
class PaypalWebCheckoutEmptyMethod implements PaymentMethodInterface
{
    /**
     * @var string
     */
    private $paymentName;

    /**
     * PaypalWebCheckoutEmptyMethod constructor.
     *
     * @param string $paymentName
     */
    public function __construct($paymentName)
    {
        $this->paymentName = $paymentName;
    }

    /**
     * Return type of payment name.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }
}
