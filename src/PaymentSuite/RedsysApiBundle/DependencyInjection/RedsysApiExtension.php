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

namespace PaymentSuite\RedsysApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RedsysApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('redsys_api.service_endpoint', $config['service_endpoint']);
        $container->setParameter('redsys_api.operation_mode', $config['operation_mode']);
        $container->setParameter('redsys_api.merchant_code', $config['merchant_code']);
        $container->setParameter('redsys_api.merchant_secret_key', $config['merchant_secret_key']);
        $container->setParameter('redsys_api.merchant_name', $config['merchant_name']);
        $container->setParameter('redsys_api.merchant_terminal', $config['merchant_terminal']);
        $container->setParameter('redsys_api.currency', $config['currency']);
        $container->setParameter('redsys_api.templates.view_template', $config['templates']['view_template']);
        $container->setParameter('redsys_api.templates.scripts_template', $config['templates']['scripts_template']);

        $container->setParameter('redsys_api.controller.route', $config['controller_route']);

        $container->setParameter('redsys_api.success.route', $config['payment_success']['route']);
        $container->setParameter('redsys_api.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('redsys_api.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('redsys_api.fail.route', $config['payment_fail']['route']);
        $container->setParameter('redsys_api.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('redsys_api.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');
        $loader->load('objectManagers.yml');
        $loader->load('repositories.yml');
    }
}
