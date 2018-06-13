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
    /**
     * @test
     */
    public function addServiceWorks()
    {
        $resolver = new PaylandsCurrencyServiceResolverTestClass($this->getPaymentBridgeMock('EUR')->reveal());

        /*
         * Fresh resolve has no service registered
         */
        $this->assertEmpty($resolver->getServices());

        /*
         * Can add a new service
         */
        $resolver->addService('EUR', 'eur-service-id');

        $this->assertNotEmpty($resolver->getServices());
        $this->assertCount(1, $resolver->getServices());
        $this->assertArrayHasKey('EUR', $resolver->getServices());
        $this->assertEquals('eur-service-id', $resolver->getServices()['EUR']);

        /*
         * Can add more than one service
         */
        $resolver->addService('USD', 'usd-service-id');

        $this->assertNotEmpty($resolver->getServices());
        $this->assertCount(2, $resolver->getServices());
        $this->assertArrayHasKey('EUR', $resolver->getServices());
        $this->assertArrayHasKey('USD', $resolver->getServices());
        $this->assertEquals('eur-service-id', $resolver->getServices()['EUR']);
        $this->assertEquals('usd-service-id', $resolver->getServices()['USD']);

        /*
         * Overwrites a service if same key is used
         */
        $resolver->addService('USD', 'usd-service-id-2');

        $this->assertNotEmpty($resolver->getServices());
        $this->assertCount(2, $resolver->getServices());
        $this->assertArrayHasKey('EUR', $resolver->getServices());
        $this->assertArrayHasKey('USD', $resolver->getServices());
        $this->assertEquals('eur-service-id', $resolver->getServices()['EUR']);
        $this->assertEquals('usd-service-id-2', $resolver->getServices()['USD']);

        return $resolver;
    }

    /**
     * @test
     * @depends addServiceWorks
     */
    public function getServiceWorks(PaylandsCurrencyServiceResolverTestClass $resolver)
    {
        $this->assertEquals('eur-service-id', $resolver->getService());

        $resolver->setPaymentBridge($this->getPaymentBridgeMock('USD')->reveal());

        $this->assertEquals('usd-service-id-2', $resolver->getService());

        $resolver->setPaymentBridge($this->getPaymentBridgeMock('GBP')->reveal());

        $this->assertEquals('', $resolver->getService());
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

/**
 * Class ExposedApiServiceResolver.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsCurrencyServiceResolverTestClass extends PaylandsCurrencyServiceResolver
{
    /**
     * @return array Services registered at the moment
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Inyects new PaymentBridgeInterface to use.
     *
     * @param PaymentBridgeInterface $paymentBridge
     */
    public function setPaymentBridge(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }
}
