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

namespace PaymentSuite\PaylandsBundle\DependencyInjection;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class PaylandsExtension.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsExtension extends AbstractPaymentSuiteExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->addParameters($container, 'paylands', [
                'api_key' => $config['api_key'],
                'signature' => $config['signature'],
                'operative' => $config['operative'],
                'sandbox' => $config['sandbox'],
                'fallback_template_uuid' => $config['fallback_template_uuid'],
                'i18n_template_uuids' => $config['i18n_template_uuids'],
                'api_url' => trim($config['sandbox'] ? $config['url_sandbox'] : $config['url'], " \t\n\r\0\x0B/"),
                'view_template' => $config['templates']['view'],
                'scripts_template' => $config['templates']['scripts'],
                'validation_service' => $config['validation_service'],
            ]
        );

        $this->registerRedirectRoutesDefinition($container, 'paylands', [
                'success' => $config['payment_success'],
                'failure' => $config['payment_failure'],
            ]
        );

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->registerApiClient($container, $config);
    }

    /**
     * Registers the list of available currency dependant Paylands' services to use.
     *
     * @param ContainerBuilder $containerBuilder
     * @param array            $config
     */
    protected function registerEndpointServices(ContainerBuilder $containerBuilder, array $config)
    {
        $resolverDefinition = $containerBuilder->getDefinition('paymentsuite.paylands.currency_service_resolver');

        foreach ($config as $option) {
            $resolverDefinition->addMethodCall('addService', [
                $option['currency'],
                $option['service'],
            ]);
        }
    }

    /**
     * Resolves configuration of needed psr-7 interfaces. When null is provided, auto-discovery is used. When
     * a service key is provided, that service is injected into related services instead.
     *
     * @param ContainerBuilder $containerBuilder
     * @param array            $config
     */
    protected function resolveApiClientInterfaces(ContainerBuilder $containerBuilder, array $config)
    {
        $requestFactoryDefinition = $containerBuilder->getDefinition('paymentsuite.paylands.api.request_factory');

        $requestFactoryDefinition->addMethodCall('setRequestFactory', [
            $config['request_factory'] ? $containerBuilder->getDefinition($config['request_factory']) : null,
        ]);

        $clientFactoryDefinition = $containerBuilder->getDefinition('paymentsuite.paylands.api.client_factory');

        $clientFactoryDefinition->addMethodCall('setHttpClient', [
            $config['http_client'] ? $containerBuilder->getDefinition($config['http_client']) : null,
        ]);

        $clientFactoryDefinition->addMethodCall('setUriFactory', [
            $config['uri_factory'] ? $containerBuilder->getDefinition($config['uri_factory']) : null,
        ]);
    }

    /**
     * Registers final API client to use, default or custom.
     *
     * @param ContainerBuilder $containerBuilder
     * @param array            $config
     */
    protected function registerApiClient(ContainerBuilder $containerBuilder, $config)
    {
        $containerBuilder->setAlias('paymentsuite.paylands.api.client', $config['api_client']);

        if (Configuration::API_CLIENT_DEFAULT == $config['api_client']) {
            $this->resolveApiClientInterfaces($containerBuilder, $config['interfaces']);

            $this->registerEndpointServices($containerBuilder, $config['services']);
        }
    }
}
