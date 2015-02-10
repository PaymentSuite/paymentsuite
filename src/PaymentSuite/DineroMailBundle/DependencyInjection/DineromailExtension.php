<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\DineroMailBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DineromailExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('dineromail.controller.route', $config['controller_route']);
        $container->setParameter('dineromail.controller.process.route', $config['controller_process_route']);

        $container->setParameter('dineromail.merchant', $config['merchant']);
        $container->setParameter('dineromail.country', $config['country']);
        $container->setParameter('dineromail.ipn_key', $config['ipn_key']);
        $container->setParameter('dineromail.seller_name', $config['seller_name']);
        $container->setParameter('dineromail.payment_methods_available', $config['payment_methods_available']);
        $container->setParameter('dineromail.url_redirect_enabled', $config['url_redirect_enabled']);
        $container->setParameter('dineromail.header_image', $config['header_image']);

        $container->setParameter('dineromail.success.route', $config['payment_success']['route']);
        $container->setParameter('dineromail.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('dineromail.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('dineromail.fail.route', $config['payment_fail']['route']);
        $container->setParameter('dineromail.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('dineromail.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
