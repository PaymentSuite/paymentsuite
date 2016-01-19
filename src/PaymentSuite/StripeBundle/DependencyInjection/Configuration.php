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

namespace PaymentSuite\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends AbstractPaymentSuiteConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('stripe');

        $rootNode->children()
            ->scalarNode('public_key')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('private_key')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('api_endpoint')
                ->defaultValue('https://api.stripe.com/')
            ->end()
            ->arrayNode('templates')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('view_template')
                        ->defaultValue('StripeBundle:Stripe:view.html.twig')
                    ->end()
                    ->scalarNode('scripts_template')
                        ->defaultValue('StripeBundle:Stripe:scripts.html.twig')
                    ->end()
                ->end()
            ->end()
            ->append($this->addRouteConfiguration('payment_success'))
            ->append($this->addRouteConfiguration('payment_failure'))
        ->end();

        return $treeBuilder;
    }
}
