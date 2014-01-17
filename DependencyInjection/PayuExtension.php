<?php

/**
 * BanwireGateway for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package BanwireGatewayBundle
 *
 */

namespace Scastells\PayuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PayuExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('payu.controller.route', $config['controller_route']);

        $container->setParameter('payu.controller.route.response', $config['controller_route_response']);

        $container->setParameter('payu.host', $config['host']);
        $container->setParameter('payu.host_report', $config['host_report']);
        $container->setParameter('payu.login', $config['login']);
        $container->setParameter('payu.key', $config['key']);
        $container->setParameter('payu.merchant_id', $config['merchant_id']);
        $container->setParameter('payu.account_id', $config['account_id']);
        $container->setParameter('payu.mode_test', $config['mode_test']);

        $container->setParameter('payu.success.route', $config['payment_success']['route']);
        $container->setParameter('payu.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('payu.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('payu.fail.route', $config['payment_fail']['route']);
        $container->setParameter('payu.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('payu.fail.order.field', $config['payment_fail']['order_append_field']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
