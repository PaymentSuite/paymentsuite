<?php

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use PaymentSuite\GestpayBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\GestpayBundle\Services\GestpayCurrencyResolver;
use PaymentSuite\GestpayBundle\Tests\Fixtures\DummyPaymentBridge;
use PHPUnit\Framework\TestCase;

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
