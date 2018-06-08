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

namespace PaymentSuite\PaylandsBundle\DependencyInjection;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteConfiguration;
use WAM\Paylands\ClientInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Configuration.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class Configuration extends AbstractPaymentSuiteConfiguration
{
    const API_CLIENT_DEFAULT = 'paymentsuite.paylands.api.client_default';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('paylands');

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('signature')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('services')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('currency')->end()
                            ->scalarNode('service')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('validation_service')
                    ->defaultNull()
                ->end()
                ->enumNode('operative')
                    ->values([
                        ClientInterface::OPERATIVE_AUTHORIZATION,
                        ClientInterface::OPERATIVE_DEFERRED,
                    ])
                    ->defaultValue(ClientInterface::OPERATIVE_AUTHORIZATION)
                ->end()
                ->scalarNode('fallback_template_uuid')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('i18n_template_uuids')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->scalarNode('url')
                    ->defaultValue('https://ws-paylands.paynopain.com/v1/')
                ->end()
                ->booleanNode('sandbox')
                    ->defaultFalse()
                ->end()
                ->scalarNode('url_sandbox')
                    ->defaultValue('https://ws-paylands.paynopain.com/v1/sandbox/')
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('view')
                            ->defaultValue('PaylandsBundle:Paylands:view.html.twig')
                        ->end()
                            ->scalarNode('scripts')
                            ->defaultValue('PaylandsBundle:Paylands:scripts.html.twig')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('interfaces')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('http_client')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('request_factory')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('uri_factory')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('api_client')
                    ->defaultValue(self::API_CLIENT_DEFAULT)
                ->end()
                ->append($this->addRouteConfiguration('payment_success'))
                ->append($this->addRouteConfiguration('payment_failure'))
                ->append($this->addRouteConfiguration('payment_card_invalid'))
            ->end();

        return $treeBuilder;
    }
}
