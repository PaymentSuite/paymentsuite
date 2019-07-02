<?php

namespace PaymentSuite\FreePaymentBundle\Tests\DependencyInjection;

use PaymentSuite\FreePaymentBundle\Controller\PaymentController;
use PaymentSuite\FreePaymentBundle\DependencyInjection\FreePaymentExtension;
use PaymentSuite\FreePaymentBundle\Services\FreePaymentManager;
use PaymentSuite\FreePaymentBundle\Services\FreePaymentMethodFactory;
use PaymentSuite\FreePaymentBundle\Services\FreePaymentSettingsProviderDefault;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FreePaymentExtensionTest extends TestCase
{
    public function testLoadDefaults()
    {
        $configs = [
            'freepayment' => [
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new FreePaymentExtension();
        $extension->load($configs, $container);

        //Routes
        $this->assertTrue($container->has('paymentsuite.freepayment.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.freepayment.route_success')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.freepayment.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.freepayment.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.freepayment.manager'));
        $this->assertSame(FreePaymentManager::class, $container->getDefinition('paymentsuite.freepayment.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.freepayment.method_factory'));
        $this->assertSame(FreePaymentMethodFactory::class, $container->getDefinition('paymentsuite.freepayment.method_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.freepayment.settings_provider_default'));
        $this->assertSame(FreePaymentSettingsProviderDefault::class, $container->getDefinition('paymentsuite.freepayment.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.freepayment.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.freepayment.settings_provider');
        $this->assertSame('paymentsuite.freepayment.settings_provider_default', $settingsProvider->__toString());
    }

    public function testLoadCustomSettingsProvider()
    {
        $configs = [
            'freepayment' => [
                'settings_provider' => 'test.dummy_settings_provider',
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new FreePaymentExtension();
        $extension->load($configs, $container);

        //Routes
        $this->assertTrue($container->has('paymentsuite.freepayment.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.freepayment.route_success')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.freepayment.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.freepayment.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.freepayment.manager'));
        $this->assertSame(FreePaymentManager::class, $container->getDefinition('paymentsuite.freepayment.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.freepayment.method_factory'));
        $this->assertSame(FreePaymentMethodFactory::class, $container->getDefinition('paymentsuite.freepayment.method_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.freepayment.settings_provider_default'));
        $this->assertSame(FreePaymentSettingsProviderDefault::class, $container->getDefinition('paymentsuite.freepayment.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.freepayment.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.freepayment.settings_provider');
        $this->assertSame('test.dummy_settings_provider', $settingsProvider->__toString());
    }
}
