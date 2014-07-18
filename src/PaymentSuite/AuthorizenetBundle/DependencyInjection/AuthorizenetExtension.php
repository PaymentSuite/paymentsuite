<?php

/**
 * AuthorizenetBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package AuthorizenetBundle
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\AuthorizenetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AuthorizenetExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('authorizenet.login.id', $config['login_id']);
        $container->setParameter('authorizenet.tran.key', $config['tran_key']);
        $container->setParameter('authorizenet.test.mode', $config['test_mode']);
        $container->setParameter('authorizenet.controller.route', $config['controller_route']);

        $container->setParameter('authorizenet.success.route', $config['payment_success']['route']);
        $container->setParameter('authorizenet.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('authorizenet.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('authorizenet.fail.route', $config['payment_fail']['route']);
        $container->setParameter('authorizenet.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('authorizenet.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
