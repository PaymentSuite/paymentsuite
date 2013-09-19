<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author David Pujadas <dpujadas@gmail.com>
 * @package DineromailBundle
 *
 * David Pujadas 2013
 */

namespace Dpujadas\DineromailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dineromail');
        $rootNode
            ->children()
                ->scalarNode('merchant')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('country_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('seller_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('language')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('currency')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('payment_method_available')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('header_image')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('controller_route')
                    ->defaultValue('/payment/dineromail/execute')
                ->end()
                ->scalarNode('controller_process_route')
                    ->defaultValue('/payment/dineromail/process/{id_order}')
                ->end()
                ->arrayNode('payment_success')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('order_append')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('order_append_field')
                            ->defaultValue('order_id')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('payment_fail')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('order_append')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('order_append_field')
                            ->defaultValue('order_id')
                        ->end()
                    ->end()
                ->end()
            ->end();
/*
        $rootNode
            ->children()
                ->scalarNode('public_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('private_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('controller_route')
                    ->defaultValue('/payment/paymill/execute')
                ->end()
                ->scalarNode('currency')
                    ->defaultValue('EUR')
                ->end()
                ->arrayNode('payment_success')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('order_append')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('order_append_field')
                            ->defaultValue('order_id')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('payment_fail')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('cart_append')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('cart_append_field')
                            ->defaultValue('cart_id')
                        ->end()
                    ->end()
                ->end()
            ->end();
*/
        return $treeBuilder;
    }
}
