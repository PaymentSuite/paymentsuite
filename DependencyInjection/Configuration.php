<?php

namespace Scastells\PagosonlineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pagosonline');

        $rootNode
        ->children()
            ->scalarNode('user_id')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('password')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('account_id')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('wsdl_url')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('controller_route')
                ->defaultValue('/payment/pagosonline/execute')
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
        ->end()
        ->end();

        return $treeBuilder;
    }
}
