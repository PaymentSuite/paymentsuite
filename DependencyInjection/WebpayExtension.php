<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WebpayExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('webpay.controller.route', $config['controller_route']);

        $container->setParameter('webpay.kcc.path', $config['kcc']['path']);
        $container->setParameter('webpay.kcc.cgi.uri', $config['kcc']['cgi_uri']);

        $container->setParameter('webpay.success.route', $config['payment_success']['route']);
        $container->setParameter('webpay.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('webpay.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('webpay.fail.route', $config['payment_fail']['route']);
        $container->setParameter('webpay.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('webpay.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
