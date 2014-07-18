<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\DependencyInjection;

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

        $container->setParameter('payu.language', $config['language']);
        $container->setParameter('payu.use.stg', $config['use_stg_servers']);
        $container->setParameter('payu.test', $config['test']);
        $container->setParameter('payu.merchant.login', $config['merchant']['login']);
        $container->setParameter('payu.merchant.key', $config['merchant']['key']);
        $container->setParameter('payu.merchant.id', $config['merchant']['id']);
        $container->setParameter('payu.merchant.account_id', $config['merchant']['account_id']);

        $container->setParameter('payu.controller.route.notify', $config['payu_controller_route_notify']);
        $container->setParameter('payu.controller.route.notify.schemes', $config['payu_controller_route_notify_schemes']);
        $container->setParameter('payu.visanet.controller.route.execute', $config['visanet_controller_route_execute']);
        $container->setParameter('payu.visanet.controller.route.execute.schemes', $config['visanet_controller_route_execute_schemes']);

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
