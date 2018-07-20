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

namespace PaymentSuite\RedsysBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends AbstractPaymentSuiteConfiguration
{
    const GATEWAY_TERMINAL = '001';

    const GATEWAY_URL = 'https://sis.redsys.es/sis/realizarPago';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redsys');

        $rootNode
            ->children()
                ->scalarNode('merchant_code')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('secret_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('terminal')
                    ->defaultValue(self::GATEWAY_TERMINAL)
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('url')
                    ->defaultValue(self::GATEWAY_URL)
                ->end()
                ->append($this->addRouteConfiguration('payment_success'))
                ->append($this->addRouteConfiguration('payment_failure'))
            ->end();

        return $treeBuilder;
    }
}
