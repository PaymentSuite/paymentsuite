<?php

namespace PaymentSuite\FreePaymentBundle\Tests\Services;

use PaymentSuite\FreePaymentBundle\FreePaymentMethod;
use PaymentSuite\FreePaymentBundle\Services\FreePaymentMethodFactory;
use PaymentSuite\FreePaymentBundle\Services\Interfaces\FreePaymentSettingsProviderInterface;
use PHPUnit\Framework\TestCase;

class FreePaymentMethodFactoryTest extends TestCase
{
    public function testCreate()
    {
        $paymentName = 'test_payment_name';

        $settingsProvider = $this->prophesize(FreePaymentSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn($paymentName);

        $factory = new FreePaymentMethodFactory($settingsProvider->reveal());

        $paymentMethod = $factory->create();

        $this->assertInstanceOf(FreePaymentMethod::class, $paymentMethod);
        $this->assertEquals($paymentName, $paymentMethod->getPaymentName());
    }
}
