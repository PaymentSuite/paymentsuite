<?php

namespace PaymentSuite\PaylandsBundle\Tests\DependencyInjection;

use PaymentSuite\PaylandsBundle\Controller\PaymentController;
use PaymentSuite\PaylandsBundle\DependencyInjection\PaylandsExtension;
use PaymentSuite\PaylandsBundle\Services\PaylandsApiAdapter;
use PaymentSuite\PaylandsBundle\Services\PaylandsCurrencyServiceResolver;
use PaymentSuite\PaylandsBundle\Services\PaylandsFormFactory;
use PaymentSuite\PaylandsBundle\Services\PaylandsManager;
use PaymentSuite\PaylandsBundle\Services\PaylandsSettingsProviderDefault;
use PaymentSuite\PaylandsBundle\Services\PaylandsViewRenderer;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WAM\Paylands\Client;
use WAM\Paylands\ClientFactory;
use WAM\Paylands\DiscoveryProxy;
use WAM\Paylands\RequestFactory;

class PaylandsExtensionTest extends TestCase
{
    public function testLoadDefaults()
    {
        $configs = [
            'paylands' => [
                'api_key' => 'test-key',
                'signature' => 'test-signature',
                'services' => [
                    ['currency' => 'EUR', 'service' => 'eur-service'],
                    ['currency' => 'USD', 'service' => 'usd-service'],
                ],
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
                'payment_card_invalid' => [
                    'route' => 'test-route-card-invalid',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new PaylandsExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.api_key'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.signature'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.operative'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.sandbox'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.fallback_template_uuid'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.i18n_template_uuids'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.api_url'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.view_template'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.scripts_template'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.validation_service'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.payment_services'));

        $this->assertSame('test-key', $container->getParameter('paymentsuite.paylands.api_key'));
        $this->assertSame('test-signature', $container->getParameter('paymentsuite.paylands.signature'));
        $this->assertSame('AUTHORIZATION', $container->getParameter('paymentsuite.paylands.operative'));
        $this->assertFalse($container->getParameter('paymentsuite.paylands.sandbox'));
        $this->assertSame('default', $container->getParameter('paymentsuite.paylands.fallback_template_uuid'));
        $this->assertSame([], $container->getParameter('paymentsuite.paylands.i18n_template_uuids'));
        $this->assertSame('https://ws-paylands.paynopain.com/v1', $container->getParameter('paymentsuite.paylands.api_url'));
        $this->assertSame('PaylandsBundle:Paylands:view.html.twig', $container->getParameter('paymentsuite.paylands.view_template'));
        $this->assertSame('PaylandsBundle:Paylands:scripts.html.twig', $container->getParameter('paymentsuite.paylands.scripts_template'));
        $this->assertNull($container->getParameter('paymentsuite.paylands.validation_service'));
        $paymentServices = [
            'EUR' => 'eur-service',
            'USD' => 'usd-service',
        ];
        $this->assertSame($paymentServices, $container->getParameter('paymentsuite.paylands.payment_services'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.paylands.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paylands.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.route_failure'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paylands.route_failure')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.route_card_invalid'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paylands.route_card_invalid')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.paylands.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.paylands.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.paylands.form_factory'));
        $this->assertSame(PaylandsFormFactory::class, $container->getDefinition('paymentsuite.paylands.form_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.manager'));
        $this->assertSame(PaylandsManager::class, $container->getDefinition('paymentsuite.paylands.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.view_renderer'));
        $this->assertSame(PaylandsViewRenderer::class, $container->getDefinition('paymentsuite.paylands.view_renderer')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.currency_service_resolver'));
        $this->assertSame(PaylandsCurrencyServiceResolver::class, $container->getDefinition('paymentsuite.paylands.currency_service_resolver')->getClass());

        //Api Services
        $this->assertTrue($container->has('paymentsuite.paylands.api.discovery_proxy'));
        $this->assertSame(DiscoveryProxy::class, $container->getDefinition('paymentsuite.paylands.api.discovery_proxy')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.request_factory'));
        $this->assertSame(RequestFactory::class, $container->getDefinition('paymentsuite.paylands.api.request_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.client_factory'));
        $this->assertSame(ClientFactory::class, $container->getDefinition('paymentsuite.paylands.api.client_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.client_default'));
        $this->assertSame(Client::class, $container->getDefinition('paymentsuite.paylands.api.client_default')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.adapter'));
        $this->assertSame(PaylandsApiAdapter::class, $container->getDefinition('paymentsuite.paylands.api.adapter')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.paylands.settings_provider_default'));
        $this->assertSame(PaylandsSettingsProviderDefault::class, $container->getDefinition('paymentsuite.paylands.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.paylands.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.paylands.settings_provider');
        $this->assertSame('paymentsuite.paylands.settings_provider_default', $settingsProvider->__toString());
    }

    public function testLoadCustomSettingsProvider()
    {
        $configs = [
            'paylands' => [
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
                'payment_card_invalid' => [
                    'route' => 'test-route-card-invalid',
                    'order_append' => true,
                    'order_append_field' => 'id',
                ],
            ],
        ];

        $container = new ContainerBuilder();

        $extension = new PaylandsExtension();
        $extension->load($configs, $container);

        //Parameters
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.api_key'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.signature'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.operative'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.sandbox'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.fallback_template_uuid'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.i18n_template_uuids'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.api_url'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.view_template'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.scripts_template'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.validation_service'));
        $this->assertTrue($container->hasParameter('paymentsuite.paylands.payment_services'));

        $this->assertSame('dummy_api_key', $container->getParameter('paymentsuite.paylands.api_key'));
        $this->assertSame('dummy_signature', $container->getParameter('paymentsuite.paylands.signature'));
        $this->assertSame('AUTHORIZATION', $container->getParameter('paymentsuite.paylands.operative'));
        $this->assertFalse($container->getParameter('paymentsuite.paylands.sandbox'));
        $this->assertSame('default', $container->getParameter('paymentsuite.paylands.fallback_template_uuid'));
        $this->assertSame([], $container->getParameter('paymentsuite.paylands.i18n_template_uuids'));
        $this->assertSame('https://ws-paylands.paynopain.com/v1', $container->getParameter('paymentsuite.paylands.api_url'));
        $this->assertSame('PaylandsBundle:Paylands:view.html.twig', $container->getParameter('paymentsuite.paylands.view_template'));
        $this->assertSame('PaylandsBundle:Paylands:scripts.html.twig', $container->getParameter('paymentsuite.paylands.scripts_template'));
        $this->assertNull($container->getParameter('paymentsuite.paylands.validation_service'));
        $paymentServices = [
            'EUR' => 'dummy_service'
        ];
        $this->assertSame($paymentServices, $container->getParameter('paymentsuite.paylands.payment_services'));

        //Routes
        $this->assertTrue($container->has('paymentsuite.paylands.route_success'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paylands.route_success')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.route_failure'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paylands.route_failure')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.route_card_invalid'));
        $this->assertSame(RedirectionRoute::class, $container->getDefinition('paymentsuite.paylands.route_card_invalid')->getClass());

        //Controllers
        $this->assertTrue($container->has('paymentsuite.paylands.payment_controller'));
        $this->assertSame(PaymentController::class, $container->getDefinition('paymentsuite.paylands.payment_controller')->getClass());

        //Services
        $this->assertTrue($container->has('paymentsuite.paylands.form_factory'));
        $this->assertSame(PaylandsFormFactory::class, $container->getDefinition('paymentsuite.paylands.form_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.manager'));
        $this->assertSame(PaylandsManager::class, $container->getDefinition('paymentsuite.paylands.manager')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.view_renderer'));
        $this->assertSame(PaylandsViewRenderer::class, $container->getDefinition('paymentsuite.paylands.view_renderer')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.currency_service_resolver'));
        $this->assertSame(PaylandsCurrencyServiceResolver::class, $container->getDefinition('paymentsuite.paylands.currency_service_resolver')->getClass());

        //Api Services
        $this->assertTrue($container->has('paymentsuite.paylands.api.discovery_proxy'));
        $this->assertSame(DiscoveryProxy::class, $container->getDefinition('paymentsuite.paylands.api.discovery_proxy')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.request_factory'));
        $this->assertSame(RequestFactory::class, $container->getDefinition('paymentsuite.paylands.api.request_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.client_factory'));
        $this->assertSame(ClientFactory::class, $container->getDefinition('paymentsuite.paylands.api.client_factory')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.client_default'));
        $this->assertSame(Client::class, $container->getDefinition('paymentsuite.paylands.api.client_default')->getClass());
        $this->assertTrue($container->has('paymentsuite.paylands.api.adapter'));
        $this->assertSame(PaylandsApiAdapter::class, $container->getDefinition('paymentsuite.paylands.api.adapter')->getClass());

        //Settings provider
        $this->assertTrue($container->has('paymentsuite.paylands.settings_provider_default'));
        $this->assertSame(PaylandsSettingsProviderDefault::class, $container->getDefinition('paymentsuite.paylands.settings_provider_default')->getClass());

        $this->assertTrue($container->has('paymentsuite.paylands.settings_provider'));
        $settingsProvider = $container->getAlias('paymentsuite.paylands.settings_provider');
        $this->assertSame('test.dummy_settings_provider', $settingsProvider->__toString());
    }
}
