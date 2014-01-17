<?php

/**
 * BanwireGatewayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package BanwireGatewayBundle
 *
 */

namespace Scastells\PayuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('payu');

        $rootNode
            ->children()
                ->scalarNode('host')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('host_report')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('login')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('merchant_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('account_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('mode_test')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('controller_route')
                    ->defaultValue('/payment/payu/execute')
                ->end()
                ->scalarNode('controller_route_response')
                    ->defaultValue('/payment/payu/response')
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

        return $treeBuilder;
    }
}
