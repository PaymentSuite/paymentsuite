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

namespace PaymentSuite\PaylandsBundle\Tests\Services;

use PaymentSuite\PaylandsBundle\Services\Interfaces\PaylandsSettingsProviderInterface;
use PaymentSuite\PaylandsBundle\Services\PaylandsCurrencyServiceResolver;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PaylandsCurrencyServiceResolverTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsCurrencyServiceResolverTest extends TestCase
{
    public function testGetServiceReturnsCorrectService()
    {
        $paymentServices = [
            'EUR' => 'eur-service-id',
        ];

        $paymentBridge = $this->getPaymentBridgeMock('EUR');

        $settingsProvider = $this->prophesize(PaylandsSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentServices()
            ->shouldBeCalled()
            ->willReturn($paymentServices);

        $resolver = new PaylandsCurrencyServiceResolver($paymentBridge->reveal(), $settingsProvider->reveal());

        $this->assertEquals('eur-service-id', $resolver->getService());
    }

    public function testGetServiceReturnsEmptyService()
    {
        $paymentServices = [
            'EUR' => 'eur-service-id',
        ];

        $paymentBridge = $this->getPaymentBridgeMock('USD');

        $settingsProvider = $this->prophesize(PaylandsSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentServices()
            ->shouldBeCalled()
            ->willReturn($paymentServices);

        $resolver = new PaylandsCurrencyServiceResolver($paymentBridge->reveal(), $settingsProvider->reveal());

        $this->assertEquals('', $resolver->getService());
    }

    public function testGetValidationServiceReturnsCorrectService()
    {
        $paymentBridge = $this->getPaymentBridgeMock('EUR');

        $settingsProvider = $this->prophesize(PaylandsSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentServices()
            ->shouldNotBeCalled();

        $settingsProvider
            ->getValidationService()
            ->shouldBeCalled()
            ->willReturn('validation-service-id');

        $resolver = new PaylandsCurrencyServiceResolver($paymentBridge->reveal(), $settingsProvider->reveal());

        $this->assertEquals('validation-service-id', $resolver->getValidationService());
    }

    public function testGetValidationServiceReturnsCurrencyServiceIfNull()
    {
        $paymentServices = [
            'EUR' => 'eur-service-id',
        ];

        $paymentBridge = $this->getPaymentBridgeMock('EUR');

        $settingsProvider = $this->prophesize(PaylandsSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentServices()
            ->shouldBeCalled()
            ->willReturn($paymentServices);

        $settingsProvider
            ->getValidationService()
            ->shouldBeCalled()
            ->willReturn(null);

        $resolver = new PaylandsCurrencyServiceResolver($paymentBridge->reveal(), $settingsProvider->reveal());

        $this->assertEquals('eur-service-id', $resolver->getValidationService());
    }

    /**
     * Returns a PaymentBridgeInterface mock.
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function getPaymentBridgeMock($currencyIso)
    {
        $bridge = $this->prophesize(PaymentBridgeInterface::class);
        $bridge
            ->getCurrency()
            ->willReturn($currencyIso);

        return $bridge;
    }
}
