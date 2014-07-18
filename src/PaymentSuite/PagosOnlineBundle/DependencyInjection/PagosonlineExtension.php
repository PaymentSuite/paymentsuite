<?php

namespace PaymentSuite\PagosonlineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class PagosonlineExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('pagosonline.controller.route', $config['controller_route']);

        $container->setParameter('pagosonline.success.route', $config['payment_success']['route']);
        $container->setParameter('pagosonline.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('pagosonline.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('pagosonline.fail.route', $config['payment_fail']['route']);
        $container->setParameter('pagosonline.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('pagosonline.fail.order.field', $config['payment_fail']['order_append_field']);

        //$container->setParameter('pagosonline.user_id', $config['user_id']);
        //$container->setParameter('pagosonline.password', $config['password']);
        $container->setParameter('pagosonline.account_id', $config['account_id']);
        //$container->setParameter('pagosonline.wsdl_url', $config['wsdl_url']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}