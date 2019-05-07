<?php

namespace PaymentSuite\BankwireBundle\Tests\Services;

use PaymentSuite\BankwireBundle\BankwireMethod;
use PaymentSuite\BankwireBundle\Services\BankwireMethodFactory;
use PaymentSuite\BankwireBundle\Services\Interfaces\BankwireSettingsProviderInterface;
use PHPUnit\Framework\TestCase;

class BankwireMethodFactoryTest extends TestCase
{
    public function testCreate()
    {
        $paymentName = 'test_payment_name';

        $settingsProvider = $this->prophesize(BankwireSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn($paymentName);

        $factory = new BankwireMethodFactory($settingsProvider->reveal());

        $paymentMethod = $factory->create();

        $this->assertInstanceOf(BankwireMethod::class, $paymentMethod);
        $this->assertEquals($paymentName, $paymentMethod->getPaymentName());
    }
}
