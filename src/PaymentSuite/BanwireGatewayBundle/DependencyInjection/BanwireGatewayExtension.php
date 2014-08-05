<?php

/**
 * BanwireGateway for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 *
 */

namespace PaymentSuite\BanwireGatewayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BanwireGatewayExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('banwire_gateway.controller.route', $config['controller_route']);

        $container->setParameter('banwire_gateway.cps', $config['cps']);
        $container->setParameter('banwire_gateway.user', $config['user']);
        $container->setParameter('banwire_gateway.controller.route.response', $config['controller_route_response']);

        $container->setParameter('banwire_gateway.success.route', $config['payment_success']['route']);
        $container->setParameter('banwire_gateway.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('banwire_gateway.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('banwire_gateway.fail.route', $config['payment_fail']['route']);
        $container->setParameter('banwire_gateway.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('banwire_gateway.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
