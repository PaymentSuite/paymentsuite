<?php

namespace PaymentSuite\StripeBundle\Tests\Services;

use PaymentSuite\StripeBundle\Services\Interfaces\StripeSettingsProviderInterface;
use PaymentSuite\StripeBundle\Services\StripeMethodFactory;
use PaymentSuite\StripeBundle\StripeMethod;
use PHPUnit\Framework\TestCase;

class StripeMethodFactoryTest extends TestCase
{
    public function testCreate()
    {
        $paymentName = 'test_payment_name';
        $apiToken = 'test_token';
        $creditCardNumber = '1234123412341234';
        $creditCardOwner = 'test owner';
        $creditCardExpirationYear = '19';
        $creditCardExpirationMonth = '12';
        $creditCardSecurity = '987';

        $settingsProvider = $this->prophesize(StripeSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn($paymentName);

        $factory = new StripeMethodFactory($settingsProvider->reveal());

        $paymentMethod = $factory->create(
            $apiToken,
            $creditCardNumber,
            $creditCardOwner,
            $creditCardExpirationYear,
            $creditCardExpirationMonth,
            $creditCardSecurity
        );

        $this->assertInstanceOf(StripeMethod::class, $paymentMethod);
        $this->assertEquals($paymentName, $paymentMethod->getPaymentName());
        $this->assertEquals($apiToken, $paymentMethod->getApiToken());
        $this->assertEquals($creditCardNumber, $paymentMethod->getCreditCardNumber());
        $this->assertEquals($creditCardOwner, $paymentMethod->getCreditCardOwner());
        $this->assertEquals($creditCardExpirationYear, $paymentMethod->getCreditCardExpirationYear());
        $this->assertEquals($creditCardExpirationMonth, $paymentMethod->getCreditCardExpirationMonth());
        $this->assertEquals($creditCardSecurity, $paymentMethod->getCreditCardSecurity());
    }
}
