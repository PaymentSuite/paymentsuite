<?php

namespace PaymentSuite\DineroMailApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class DineromailApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('dineromail_api.controller.route', $config['controller_route']);

        $container->setParameter('dineromail_api.success.route', $config['payment_success']['route']);
        $container->setParameter('dineromail_api.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('dineromail_api.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('dineromail_api.fail.route', $config['payment_fail']['route']);
        $container->setParameter('dineromail_api.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('dineromail_api.fail.order.field', $config['payment_fail']['order_append_field']);

        $container->setParameter('dineromail_api.wsdl_path', $config['wsdl_path']);
        $container->setParameter('dineromail_api.ns', $config['ns']);
        $container->setParameter('dineromail_api.api_user_name', $config['api_user_name']);
        $container->setParameter('dineromail_api.api_password', $config['api_password']);
        $container->setParameter('dineromail_api.api_prefix', $config['api_prefix']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
