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

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use PaymentSuite\GestpayBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\GestpayBundle\Services\GestpayCurrencyResolver;
use PaymentSuite\GestpayBundle\Tests\Fixtures\DummyPaymentBridge;
use PHPUnit\Framework\TestCase;

/**
 * Class GestpayCurrencyResolverTest
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayCurrencyResolverTest extends TestCase
{
    public function testGetCurrencyCodeGetsTheRightCode()
    {
        $paymentBridge = new DummyPaymentBridge();
        $paymentBridge->setCurrency('EUR');

        $resolver = new GestpayCurrencyResolver($paymentBridge);

        $this->assertEquals(242, $resolver->getCurrencyCode());
    }

    public function testGetCurrencyCodeThrowsExceptionIfNotSupportedCurrency()
    {
        $paymentBridge = new DummyPaymentBridge();
        $paymentBridge->setCurrency('ABC');

        $resolver = new GestpayCurrencyResolver($paymentBridge);

        $this->expectException(CurrencyNotSupportedException::class);
        $resolver->getCurrencyCode();
    }
}
