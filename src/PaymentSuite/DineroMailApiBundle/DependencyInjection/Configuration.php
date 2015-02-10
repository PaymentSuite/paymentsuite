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

namespace PaymentSuite\DineroMailApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dineromail_api');

        $rootNode
        ->children()
            ->scalarNode('api_user_name')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('api_password')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('api_prefix')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('wsdl_path')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('ns')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('controller_route')
                ->defaultValue('/payment/dineromailapi/execute')
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
