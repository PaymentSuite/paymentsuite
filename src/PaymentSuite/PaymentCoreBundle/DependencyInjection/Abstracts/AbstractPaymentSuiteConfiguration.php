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

    /**
     * Adds common configuration for every payment method given a root node.
     *
     * @param NodeDefinition $rootNode
     */
    final protected function addSettingsProviderConfiguration(NodeDefinition $rootNode): void
    {
        $rootNode
            ->beforeNormalization()
                ->ifTrue(function ($v) {
                    return isset($v['settings_provider']) && 'default' != $v['settings_provider'];
                })
                ->then(function ($v) {
                    return static::setDefaultSettings($v);
                })
            ->end()
            ->children()
                ->scalarNode('settings_provider')
                    ->defaultValue('default')
                ->end()
            ->end();
    }

    /**
     * Sets default values for required custom settings.
     *
     * @param array $config
     *
     * @return array
     */
    protected function setDefaultSettings(array $config): array
    {
        return $config;
    }
}
