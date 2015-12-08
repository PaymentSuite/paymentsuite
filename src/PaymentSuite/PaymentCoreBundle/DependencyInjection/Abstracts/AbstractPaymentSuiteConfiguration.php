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

namespace PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class AbstractPaymentSuiteConfiguration.
 */
abstract class AbstractPaymentSuiteConfiguration implements ConfigurationInterface
{
    /**
     * Add a new success route in configuration.
     *
     * @param string $routeName Route name
     *
     * @return NodeDefinition Node
     */
    protected function addRouteConfiguration($routeName)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($routeName);
        $node
            ->isRequired()
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
            ->end();

        return $node;
    }
}
