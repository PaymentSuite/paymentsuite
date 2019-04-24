<?php

namespace PaymentSuite\BankwireBundle\Tests\DependencyInjection;

use PaymentSuite\BankwireBundle\Controller\PaymentController;
use PaymentSuite\BankwireBundle\DependencyInjection\BankwireExtension;
use PaymentSuite\BankwireBundle\Services\BankwireManager;
use PaymentSuite\BankwireBundle\Services\BankwireMethodFactory;
use PaymentSuite\BankwireBundle\Services\BankwireSettingsProviderDefault;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BankwireExtensionTest extends TestCase
{
    public function testLoadDefaults()
    {
        $configs = [
            'bankwire' => [
                'settings_provider' => 'default',
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new BankwireExtension();
        $extension->load($configs, $container);

        //Routes
        $this->assertTrue($container->has('paymentsuite.bankwire.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.bankwire.route_success')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.bankwire.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.bankwire.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.bankwire.manager'));
        $this->assertSame(BankwireManager::class, $container->getDefinition('paymentsuite.bankwire.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.bankwire.method_factory'));
        $this->assertSame(BankwireMethodFactory::class, $container->getDefinition('paymentsuite.bankwire.method_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.bankwire.settings_provider_default'));
        $this->assertSame(BankwireSettingsProviderDefault::class, $container->getDefinition('paymentsuite.bankwire.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.bankwire.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.bankwire.settings_provider');
        $this->assertSame('paymentsuite.bankwire.settings_provider_default', $settingsProvider->__toString());
    }

    public function testLoadCustomSettingsProvider()
    {
        $configs = [
            'bankwire' => [
                'settings_provider' => 'test.dummy_settings_provider',
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new BankwireExtension();
        $extension->load($configs, $container);

        //Routes
        $this->assertTrue($container->has('paymentsuite.bankwire.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.bankwire.route_success')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.bankwire.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.bankwire.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.bankwire.manager'));
        $this->assertSame(BankwireManager::class, $container->getDefinition('paymentsuite.bankwire.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.bankwire.method_factory'));
        $this->assertSame(BankwireMethodFactory::class, $container->getDefinition('paymentsuite.bankwire.method_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.bankwire.settings_provider_default'));
        $this->assertSame(BankwireSettingsProviderDefault::class, $container->getDefinition('paymentsuite.bankwire.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.bankwire.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.bankwire.settings_provider');
        $this->assertSame('test.dummy_settings_provider', $settingsProvider->__toString());
    }
}
