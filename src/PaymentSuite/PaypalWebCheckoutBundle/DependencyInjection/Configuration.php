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

namespace PaymentSuite\PaypalWebCheckoutBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration extends AbstractPaymentSuiteConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('paypal_web_checkout');

        $rootNode
            ->children()
                ->scalarNode('business')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('debug')
                    ->defaultTrue()
                ->end()
                ->scalarNode('api_endpoint')
                    ->defaultValue('https://www.paypal.com/cgi-bin/webscr')
                ->end()
                ->scalarNode('sandbox_api_endpoint')
                    ->defaultValue('https://www.sandbox.paypal.com/cgi-bin/webscr')
                ->end()
                ->append($this->addRouteConfiguration('payment_success'))
                ->append($this->addRouteConfiguration('payment_cancel'))
            ->end();

        $this->addSettingsProviderConfiguration($rootNode);

        return $treeBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultSettings($config)
    {
        $config['business'] = 'dummy@paypal.com';

        return $config;
    }
}
