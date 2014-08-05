<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaymillBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PaymillExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('paymill.private.key', $config['private_key']);
        $container->setParameter('paymill.public.key', $config['public_key']);
        $container->setParameter('paymill.controller.route', $config['controller_route']);

        $container->setParameter('paymill.success.route', $config['payment_success']['route']);
        $container->setParameter('paymill.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('paymill.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('paymill.fail.route', $config['payment_fail']['route']);
        $container->setParameter('paymill.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('paymill.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
