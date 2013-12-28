<?php

/**
 * PaypalBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalBundle
 *
 * Mickael Andrieu 2013
 */

namespace Mmoreram\PaypalBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PaypalExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('paypal.rest_api.client_id', $config['rest_api']['client_id']);
        $container->setParameter('paypal.rest_api.secret', $config['rest_api']['secret']);
        $container->setParameter('paypal.controller.route', $config['controller_route']);

        $container->setParameter('paypal.http.connection_timeout', $config['http']['connection_timeout']);
        $container->setParameter('paypal.http.retry', $config['http']['retry']);

        $container->setParameter('paypal.service.mode', $config['service']['mode']);

        $container->setParameter('paypal.log.enabled', $config['log']['enabled']);
        $container->setParameter('paypal.log.filename', $config['log']['filename']);
        $container->setParameter('paypal.log.log_level', $config['log']['log_level']);

        $container->setParameter('paypal.success.route', $config['payment_success']['route']);
        $container->setParameter('paypal.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('paypal.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('paypal.fail.route', $config['payment_fail']['route']);
        $container->setParameter('paypal.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('paypal.fail.order.field', $config['payment_fail']['order_append_field']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
