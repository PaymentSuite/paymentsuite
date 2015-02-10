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

namespace PaymentSuite\PayUBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('payu');

        $rootNode
            ->children()
                ->scalarNode('language')
                    ->isRequired()
                    ->validate()
                    ->ifNotInArray(array('en', 'es', 'pt'))
                        ->thenInvalid('Invalid language "%s"')
                    ->end()
                ->end()
                ->booleanNode('test')
                    ->defaultFalse()
                ->end()
                ->booleanNode('use_stg_servers')
                    ->defaultFalse()
                ->end()
                ->arrayNode('merchant')
                    ->children()
                        ->scalarNode('login')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('account_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('visanet_controller_route_execute')
                    ->defaultValue('/payment/visanet/execute')
                ->end()
                ->scalarNode('visanet_controller_route_execute_schemes')
                    ->defaultValue('https')
                ->end()
                ->scalarNode('payu_controller_route_notify')
                    ->defaultValue('/payment/payu/notify')
                ->end()
                ->scalarNode('payu_controller_route_notify_schemes')
                    ->defaultValue('http')
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
