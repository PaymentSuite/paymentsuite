<?php

/**
 * Safetypay for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package SafetypayBundle
 *
 */

namespace Scastells\SafetypayBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SafetypayExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('safetypay.controller.route', $config['controller_route']);

        $container->setParameter('safetypay.key', $config['key']);
        $container->setParameter('safetypay.signature', $config['signature']);
        $container->setParameter('safetypay.token', $config['token']);
        $container->setParameter('safetypay.response.format', $config['response_format']);
        $container->setParameter('safetypay.expiration', $config['expiration']);
        $container->setParameter('safetypay.controller.route.confirm', $config['controller_route_confirm']);

        $container->setParameter('safetypay.success.route', $config['payment_success']['route']);
        $container->setParameter('safetypay.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('safetypay.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('safetypay.fail.route', $config['payment_fail']['route']);
        $container->setParameter('safetypay.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('safetypay.fail.order.field', $config['payment_fail']['order_append_field']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
