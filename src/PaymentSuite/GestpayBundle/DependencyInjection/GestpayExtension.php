<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\GestpayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteExtension;

/**
 * Class GestpayExtension
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayExtension extends AbstractPaymentSuiteExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->addParameters(
            $container,
            'gestpay',
            [
                'shop_login' => $config['shop_login'],
                'api_key' => $config['api_key'],
                'sandbox' => $config['sandbox'],
            ]
        );

        $this->registerRedirectRoutesDefinition(
            $container,
            'gestpay',
            [
                'success' => $config['payment_success'],
                'failure' => $config['payment_failure'],
            ]
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('controllers.yml');
        $loader->load('services.yml');

        $this->addSettingsProvider(
            $container,
            'gestpay',
            $config['settings_provider']
        );
    }
}
