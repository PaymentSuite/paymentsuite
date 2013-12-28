<?php

/**
 * PaypalBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalBundle
 *
 * Mickael Andrieu 2013
 */

namespace Mandrieu\PaypalBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('paypal');

        $rootNode
            ->children()
                ->arrayNode('rest_api')
                    ->children()
                        ->scalarNode('client_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                ->end()
                ->arrayNode('http')
                    ->children()
                        ->scalarNode('connection_timeout')
                            ->defaultValue(30)
                        ->end()
                        ->scalarNode('retry')
                            ->defaultValue(1)
                        ->end()
                    ->end()
                ->arrayNode('service')
                    ->children()
                        ->scalarNode('mode')
                            ->defaultValue('sandbox')
                            ->validate()
                            ->ifNotInArray(array('sandbox', 'live'))
                                ->thenInvalid('Invalid mode "%s"')
                            ->end()
                        ->end()
                    ->end()
                ->arrayNode('log')
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('filename')
                            ->defaultValue('../paypal.log')
                        ->end()
                        ->scalarNode('log_level')
                            ->defaultValue('FINE')
                            ->validate()
                            ->ifNotInArray(array('FINE', 'INFO', 'WARN', 'ERROR'))
                                ->thenInvalid('Invalid log level "%s"')
                            ->end()
                        ->end()
                    ->end()


                ->scalarNode('controller_route')
                    ->defaultValue('/payment/paypal/execute')
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
