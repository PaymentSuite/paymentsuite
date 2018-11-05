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

namespace PaymentSuite\GestpayBundle\Tests\Unit\DependencyInjection;

use EndelWar\GestPayWS\WSCryptDecrypt;
use PaymentSuite\GestpayBundle\Controller\PaymentController;
use PaymentSuite\GestpayBundle\Controller\ResponseController;
use PaymentSuite\GestpayBundle\DependencyInjection\GestpayExtension;
use PaymentSuite\GestpayBundle\Services\GestpayCurrencyResolver;
use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Services\GestpayManager;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GestpayExtensionTest
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayExtensionTest extends TestCase
{
    public function testExtensionLoadsSuccessfully()
    {
        $configs = [
            'gestpay' => [
                'shop_login' => 'test-login',
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
                'payment_failure' => [
                    'route' => 'test-route-failure',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new GestpayExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.gestpay.shop_login'));
        $this->assertTrue($container->hasParameter('paymentsuite.gestpay.sandbox'));
        $this->assertTrue($container->hasParameter('paymentsuite.gestpay.api_key'));

        $this->assertSame('test-login', $container->getParameter('paymentsuite.gestpay.shop_login'));
        $this->assertSame(false, $container->getParameter('paymentsuite.gestpay.sandbox'));
        $this->assertSame(null, $container->getParameter('paymentsuite.gestpay.api_key'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.gestpay.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.gestpay.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.gestpay.route_failure'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.gestpay.route_failure')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.gestpay.encrypt_client'));
        $this->assertSame(WSCryptDecrypt::class, $container->getDefinition('paymentsuite.gestpay.encrypt_client')->getClass());
        $this->assertTrue($container->has('paymentsuite.gestpay.currency_resolver'));
        $this->assertSame(GestpayCurrencyResolver::class, $container->getDefinition('paymentsuite.gestpay.currency_resolver')->getClass());
        $this->assertTrue($container->has('paymentsuite.gestpay.encrypter'));
        $this->assertSame(GestpayEncrypter::class, $container->getDefinition('paymentsuite.gestpay.encrypter')->getClass());
        $this->assertTrue($container->has('paymentsuite.gestpay.manager'));
        $this->assertSame(GestpayManager::class, $container->getDefinition('paymentsuite.gestpay.manager')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.gestpay.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.gestpay.payment_controller')->getClass());
        $this->assertTrue($container->has('paymentsuite.gestpay.response_controller'));
        $this->assertSame(ResponseController::class, $container->getDefinition('paymentsuite.gestpay.response_controller')->getClass());
    }
}
