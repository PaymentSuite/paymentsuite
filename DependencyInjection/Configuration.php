<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\DependencyInjection;

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
                    ->end()
                ->end()
                ->scalarNode('visanet_controller_route_execute')
                    ->defaultValue('/payment/visanet/execute')
                ->end()
                ->scalarNode('visanet_controller_route_execute_schemes')
                    ->defaultValue('https')
                ->end()
                ->scalarNode('encoder_class')
                    ->defaultValue('Symfony\Component\Serializer\Encoder\JsonEncoder')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
