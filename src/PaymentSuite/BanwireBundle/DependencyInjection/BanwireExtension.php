<?php

/**
 * BanwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BanwireBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\BanwireBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BanwireExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('banwire.controller.route',$config['controller_route']);

        $container->setParameter('banwire.success.route',$config['payment_success']['route']);
        $container->setParameter('banwire.success.order.append',$config['payment_success']['order_append']);
        $container->setParameter('banwire.success.order.field',$config['payment_success']['order_append_field']);

        $container->setParameter('banwire.fail.route',$config['payment_fail']['route']);
        $container->setParameter('banwire.fail.order.append',$config['payment_fail']['order_append']);
        $container->setParameter('banwire.fail.order.field',$config['payment_fail']['order_append_field']);

        $container->setParameter('banwire.user',$config['user']);
        $container->setParameter('banwire.api',$config['api']);

        $loader = new Loader\YamlFileLoader($container,new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
