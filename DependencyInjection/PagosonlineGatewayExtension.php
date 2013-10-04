<?php

/**
 * PagosonlineGateway for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PagosonlineGateway
 *
 */

namespace Mmoreram\DineromailBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PagosonlineGatewayExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('pagosonlinegateway.controller.route', $config['controller_route']);

        $container->setParameter('pagosonlinegateway.key', $config['key']);
        $container->setParameter('pagosonlinegateway.user_id', $config['user_id']);
        $container->setParameter('pagosonlinegateway.test', $config['test']);
        $container->setParameter('pagosonlinegateway.gateway', $config['gateway']);
        $container->setParameter('pagosonlinegateway.url_redirect_enabled', $config['url_redirect_enabled']);

        $container->setParameter('pagosonlinegateway.success.route', $config['payment_success']['route']);
        $container->setParameter('pagosonlinegateway.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('pagosonlinegateway.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('pagosonlinegateway.fail.route', $config['payment_fail']['route']);
        $container->setParameter('pagosonlinegateway.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('pagosonlinegateway.fail.order.field', $config['payment_fail']['order_append_field']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
