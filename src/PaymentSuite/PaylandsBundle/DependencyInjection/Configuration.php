<?php

namespace PaymentSuite\PaylandsBundle\DependencyInjection;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteConfiguration;
use PaymentSuite\PaylandsBundle\ApiClient\ApiClientInterface;
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
                ->enumNode('operative')
                    ->values([
                        ApiClientInterface::OPERATIVE_AUTHORIZATION,
                        ApiClientInterface::OPERATIVE_DEFERRED,
                    ])
                    ->defaultValue(ApiClientInterface::OPERATIVE_AUTHORIZATION)
                ->end()
                ->scalarNode('custom_template_uuid')
                    ->defaultValue('default')
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
            ->end();

        return $treeBuilder;
    }
}
