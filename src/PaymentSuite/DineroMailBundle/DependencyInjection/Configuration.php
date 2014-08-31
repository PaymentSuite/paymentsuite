<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\DineroMailBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('dineromail');

        $rootNode
            ->children()
                ->scalarNode('merchant')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('ipn_key')
                    ->defaultValue('')
                ->end()
                ->scalarNode('country')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('seller_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('payment_methods_available')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->scalarNode('header_image')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('url_redirect_enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('controller_route')
                    ->defaultValue('/payment/dineromail/execute')
                ->end()
                ->scalarNode('controller_process_route')
                    ->defaultValue('/payment/dineromail/process')
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
