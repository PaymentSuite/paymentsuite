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

namespace PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class AbstractPaymentSuiteExtension.
 */
abstract class AbstractPaymentSuiteExtension extends Extension
{
    /**
     * Create a new service for the RedirectRoutes collection given a
     * configuration array.
     *
     * This method will register each redirection route in a separated service
     * and will create a new service for the collection
     *
     * The value entered must have this format
     *
     * [ 'name' => [
     *      'route' => 'xxx',
     *      'order_append' => 'xxx',
     *      'order_append_field' => 'xxx',
     *  ],
     * [ 'name2' => [
     *      'route' => 'xxx',
     *      'order_append' => 'xxx',
     *      'order_append_field' => 'xxx',
     *  ]
     *
     * Definitions will have this format
     *
     * paymentsuite.{paymentName}.routes
     * paymentsuite.{paymentName}.route_success
     * paymentsuite.{paymentName}.route_failure
     *
     * @param ContainerBuilder $containerBuilder               Container builder
     * @param string           $paymentName                    Payment name
     * @param array            $redirectionRoutesConfiguration Redirection routes configuration
     */
    protected function registerRedirectRoutesDefinition(
        ContainerBuilder $containerBuilder,
        string $paymentName,
        array $redirectionRoutesConfiguration
    ): void {
        $collectionDefinition = $containerBuilder->register(
            "paymentsuite.{$paymentName}.routes",
            'PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection'
        );

        foreach ($redirectionRoutesConfiguration as $redirectRouteName => $redirectRouteConfiguration) {
            $serviceName = "paymentsuite.$paymentName.route_$redirectRouteName";
            $routeDefinition = $containerBuilder->register(
                $serviceName,
                'PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute'
            );

            $routeDefinition
                ->addArgument($redirectRouteConfiguration['route'])
                ->addArgument($redirectRouteConfiguration['order_append'])
                ->addArgument($redirectRouteConfiguration['order_append_field']);

            $collectionDefinition->addMethodCall(
                'addRedirectionRoute',
                [
                    new Reference($serviceName),
                    $redirectRouteName,
                ]
            );
        }
    }

    /**
     * Add parameters and values in a bulk way.
     *
     * @param ContainerBuilder $containerBuilder Container builder
     * @param string           $paymentName      Payment name
     * @param array            $configuration    Configuration
     */
    protected function addParameters(
        ContainerBuilder $containerBuilder,
        string $paymentName,
        array $configuration
    ): void {
        foreach ($configuration as $key => $value) {
            $parameterName = '' === $paymentName
                ? "paymentsuite.$key"
                : "paymentsuite.$paymentName.$key";

            $containerBuilder->setParameter(
                $parameterName,
                $value
            );
        }
    }

    /**
     * Defines the alias for the settings provider services.
     *
     * @param ContainerBuilder $container
     * @param string           $paymentName
     * @param string           $settingsProviderConfiguration
     */
    protected function addSettingsProvider(
        ContainerBuilder $container,
        string $paymentName,
        string $settingsProviderConfiguration
    ): void {
        $serviceId = 'default' == $settingsProviderConfiguration
            ? "paymentsuite.$paymentName.settings_provider_default"
            : $settingsProviderConfiguration;

        $container->setAlias("paymentsuite.$paymentName.settings_provider", $serviceId)
            ->setPublic(true);

    }
}
