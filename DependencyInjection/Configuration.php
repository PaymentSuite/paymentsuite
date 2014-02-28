<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webpay');

        $rootNode
            ->children()
                ->scalarNode('controller_route')
                    ->defaultValue('/payment/webpay/execute')
                ->end()
                ->arrayNode('kcc')
                    ->children()
                        ->scalarNode('path')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('cgi_uri')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
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
                            ->defaultValue('card_id')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
