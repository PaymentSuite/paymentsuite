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

namespace PaymentSuite\WebpayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        $container->setParameter('webpay.controller.route.execute', $config['controller_route_execute']);
        $container->setParameter('webpay.controller.route.execute.schemes', $config['controller_route_execute_schemes']);
        $container->setParameter('webpay.controller.route.confirmation', $config['controller_route_confirmation']);
        $container->setParameter('webpay.controller.route.confirmation.schemes', $config['controller_route_confirmation_schemes']);

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
