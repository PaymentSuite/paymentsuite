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

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PaypalExpressCheckoutExtension extends AbstractPaymentSuiteExtension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container
            ->register(
                'paymentsuite.paypal_express_checkout.requester',
                'PaymentSuite\PaypalExpressCheckoutBundle\Services\PaypalExpressCheckoutRequester'
            )
            ->addArgument($config['username'])
            ->addArgument($config['password'])
            ->addArgument($config['signature'])
            ->addArgument($config['api_endpoint'])
            ->addArgument($config['debug']);

        $container->setParameter(
            'paymentsuite.paypal_express_checkout.routes',
            $this->createCompleteRedirectRouteByConfiguration(
                $config['payment_success'],
                $config['payment_failure']
            )
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('controllers.yml');
        $loader->load('services.yml');
    }
}
