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

namespace PaymentSuite\PaypalExpressCheckoutBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PaypalExpressCheckoutExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('paypal_express_checkout.username', $config['username']);
        $container->setParameter('paypal_express_checkout.password', $config['password']);
        $container->setParameter('paypal_express_checkout.signature', $config['signature']);
        $container->setParameter('paypal_express_checkout.debug', $config['debug']);
        $container->setParameter('paypal_express_checkout.controller.route', $config['controller_route']);

        $container->setParameter('paypal_express_checkout.success.route', $config['payment_success']['route']);
        $container->setParameter('paypal_express_checkout.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('paypal_express_checkout.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('paypal_express_checkout.fail.route', $config['payment_fail']['route']);
        $container->setParameter('paypal_express_checkout.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('paypal_express_checkout.fail.order.field', $config['payment_fail']['order_append_field']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
