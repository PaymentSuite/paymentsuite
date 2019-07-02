<?php

namespace PaymentSuite\StripeBundle\Tests\DependencyInjection;

use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PaymentSuite\StripeBundle\Controller\PaymentController;
use PaymentSuite\StripeBundle\DependencyInjection\StripeExtension;
use PaymentSuite\StripeBundle\Services\StripeEventDispatcher;
use PaymentSuite\StripeBundle\Services\StripeManager;
use PaymentSuite\StripeBundle\Services\StripeMethodFactory;
use PaymentSuite\StripeBundle\Services\StripeSettingsProviderDefault;
use PaymentSuite\StripeBundle\Services\StripeTemplateRender;
use PaymentSuite\StripeBundle\Services\StripeTransactionFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StripeExtensionTest extends TestCase
{
    public function testLoadDefaults()
    {
        $configs = [
            'stripe' => [
                'settings_provider' => 'default',
                'public_key' => 'public-key',
                'private_key' => 'private-key',
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

        $extension = new StripeExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.private_key'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.public_key'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.api_endpoint'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.view_template'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.scripts_template'));

        $this->assertSame('private-key', $container->getParameter('paymentsuite.stripe.private_key'));
        $this->assertSame('public-key', $container->getParameter('paymentsuite.stripe.public_key'));
        $this->assertSame('https://api.stripe.com/', $container->getParameter('paymentsuite.stripe.api_endpoint'));
        $this->assertSame('StripeBundle:Stripe:view.html.twig', $container->getParameter('paymentsuite.stripe.view_template'));
        $this->assertSame('StripeBundle:Stripe:scripts.html.twig', $container->getParameter('paymentsuite.stripe.scripts_template'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.stripe.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.stripe.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.route_failure'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.stripe.route_failure')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.stripe.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.stripe.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.stripe.event_dispatcher'));
        $this->assertSame(StripeEventDispatcher::class, $container->getDefinition('paymentsuite.stripe.event_dispatcher')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.manager'));
        $this->assertSame(StripeManager::class, $container->getDefinition('paymentsuite.stripe.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.template_render'));
        $this->assertSame(StripeTemplateRender::class, $container->getDefinition('paymentsuite.stripe.template_render')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.method_factory'));
        $this->assertSame(StripeMethodFactory::class, $container->getDefinition('paymentsuite.stripe.method_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.transaction_factory'));
        $this->assertSame(StripeTransactionFactory::class, $container->getDefinition('paymentsuite.stripe.transaction_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.stripe.settings_provider_default'));
        $this->assertSame(StripeSettingsProviderDefault::class, $container->getDefinition('paymentsuite.stripe.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.stripe.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.stripe.settings_provider');
        $this->assertSame('paymentsuite.stripe.settings_provider_default', $settingsProvider->__toString());
    }

    public function testLoadCustomSettingsProvider()
    {
        $configs = [
            'stripe' => [
                'settings_provider' => 'test.dummy_settings_provider',
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

        $extension = new StripeExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.private_key'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.public_key'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.api_endpoint'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.view_template'));
        $this->assertTrue($container->hasParameter('paymentsuite.stripe.scripts_template'));

        $this->assertSame('private_dummy', $container->getParameter('paymentsuite.stripe.private_key'));
        $this->assertSame('public_dummy', $container->getParameter('paymentsuite.stripe.public_key'));
        $this->assertSame('https://api.stripe.com/', $container->getParameter('paymentsuite.stripe.api_endpoint'));
        $this->assertSame('StripeBundle:Stripe:view.html.twig', $container->getParameter('paymentsuite.stripe.view_template'));
        $this->assertSame('StripeBundle:Stripe:scripts.html.twig', $container->getParameter('paymentsuite.stripe.scripts_template'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.stripe.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.stripe.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.route_failure'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.stripe.route_failure')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.stripe.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.stripe.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.stripe.event_dispatcher'));
        $this->assertSame(StripeEventDispatcher::class, $container->getDefinition('paymentsuite.stripe.event_dispatcher')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.manager'));
        $this->assertSame(StripeManager::class, $container->getDefinition('paymentsuite.stripe.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.template_render'));
        $this->assertSame(StripeTemplateRender::class, $container->getDefinition('paymentsuite.stripe.template_render')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.method_factory'));
        $this->assertSame(StripeMethodFactory::class, $container->getDefinition('paymentsuite.stripe.method_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.stripe.transaction_factory'));
        $this->assertSame(StripeTransactionFactory::class, $container->getDefinition('paymentsuite.stripe.transaction_factory')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.stripe.settings_provider_default'));
        $this->assertSame(StripeSettingsProviderDefault::class, $container->getDefinition('paymentsuite.stripe.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.stripe.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.stripe.settings_provider');
        $this->assertSame('test.dummy_settings_provider', $settingsProvider->__toString());
    }
}
