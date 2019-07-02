<?php

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\DependencyInjection;

use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PaymentSuite\PaypalWebCheckoutBundle\Controller\PaymentController;
use PaymentSuite\PaypalWebCheckoutBundle\Controller\ProcessController;
use PaymentSuite\PaypalWebCheckoutBundle\DependencyInjection\PaypalWebCheckoutExtension;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutFormTypeFactory;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutManager;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutMethodFactory;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutSettingsProviderDefault;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutUrlFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaypalWebCheckoutExtensionTest extends TestCase
{
    public function testLoadDefaults()
    {
        $configs = [
            'paypal_web_checkout' => [
                'settings_provider' => 'default',
                'business' => 'test@paypal.com',
                'debug' => true,
                'api_endpoint' => 'https://www.paypal.com/cgi-bin/webscr',
                'sandbox_api_endpoint' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
                'payment_cancel' => [
                    'route' => 'test-route-failure',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new PaypalWebCheckoutExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.paypal_web_checkout.business'));
        $this->assertTrue($container->hasParameter('paymentsuite.paypal_web_checkout.api_endpoint'));
        $this->assertTrue($container->hasParameter('paymentsuite.paypal_web_checkout.debug'));

        $this->assertSame('test@paypal.com', $container->getParameter('paymentsuite.paypal_web_checkout.business'));
        $this->assertSame(true, $container->getParameter('paymentsuite.paypal_web_checkout.debug'));
        $this->assertSame('https://www.sandbox.paypal.com/cgi-bin/webscr', $container->getParameter('paymentsuite.paypal_web_checkout.api_endpoint'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paypal_web_checkout.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.route_cancel'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paypal_web_checkout.route_cancel')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.paypal_web_checkout.payment_controller')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.process_controller'));
        $this->assertSame(ProcessController::class, $container->getDefinition('paymentsuite.paypal_web_checkout.process_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.form_type_factory'));
        $this->assertSame(PaypalWebCheckoutFormTypeFactory::class, $container->getDefinition('paymentsuite.paypal_web_checkout.form_type_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.manager'));
        $this->assertSame(PaypalWebCheckoutManager::class, $container->getDefinition('paymentsuite.paypal_web_checkout.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.url_factory'));
        $this->assertSame(PaypalWebCheckoutUrlFactory::class, $container->getDefinition('paymentsuite.paypal_web_checkout.url_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.method_factory'));
        $this->assertSame(PaypalWebCheckoutMethodFactory::class, $container->getDefinition('paymentsuite.paypal_web_checkout.method_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.settings_provider_default'));
        $this->assertSame(PaypalWebCheckoutSettingsProviderDefault::class, $container->getDefinition('paymentsuite.paypal_web_checkout.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.paypal_web_checkout.settings_provider');
        $this->assertSame('paymentsuite.paypal_web_checkout.settings_provider_default', $settingsProvider->__toString());
    }

    public function testLoadCustomSettingsProvider()
    {
        $configs = [
            'paypal_web_checkout' => [
                'settings_provider' => 'test.dummy_settings_provider',
                'debug' => true,
                'payment_success' => [
                    'route' => 'test-route-success',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
                'payment_cancel' => [
                    'route' => 'test-route-failure',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new PaypalWebCheckoutExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.paypal_web_checkout.business'));
        $this->assertTrue($container->hasParameter('paymentsuite.paypal_web_checkout.api_endpoint'));
        $this->assertTrue($container->hasParameter('paymentsuite.paypal_web_checkout.debug'));

        $this->assertSame('dummy@paypal.com', $container->getParameter('paymentsuite.paypal_web_checkout.business'));
        $this->assertSame(true, $container->getParameter('paymentsuite.paypal_web_checkout.debug'));
        $this->assertSame('https://www.sandbox.paypal.com/cgi-bin/webscr', $container->getParameter('paymentsuite.paypal_web_checkout.api_endpoint'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paypal_web_checkout.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.route_cancel'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paypal_web_checkout.route_cancel')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.paypal_web_checkout.payment_controller')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.process_controller'));
        $this->assertSame(ProcessController::class, $container->getDefinition('paymentsuite.paypal_web_checkout.process_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.form_type_factory'));
        $this->assertSame(PaypalWebCheckoutFormTypeFactory::class, $container->getDefinition('paymentsuite.paypal_web_checkout.form_type_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.manager'));
        $this->assertSame(PaypalWebCheckoutManager::class, $container->getDefinition('paymentsuite.paypal_web_checkout.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.url_factory'));
        $this->assertSame(PaypalWebCheckoutUrlFactory::class, $container->getDefinition('paymentsuite.paypal_web_checkout.url_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.method_factory'));
        $this->assertSame(PaypalWebCheckoutMethodFactory::class, $container->getDefinition('paymentsuite.paypal_web_checkout.method_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.settings_provider_default'));
        $this->assertSame(PaypalWebCheckoutSettingsProviderDefault::class, $container->getDefinition('paymentsuite.paypal_web_checkout.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.paypal_web_checkout.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.paypal_web_checkout.settings_provider');
        $this->assertSame('test.dummy_settings_provider', $settingsProvider->__toString());
    }
}
