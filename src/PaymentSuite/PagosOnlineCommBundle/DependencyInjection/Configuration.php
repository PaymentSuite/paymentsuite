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

namespace PaymentSuite\PagosOnlineCommBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pagosonline_comm');

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
            ->scalarNode('wsdl_url')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
