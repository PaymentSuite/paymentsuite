<?php

namespace PaymentSuite\RedsysBundle\Tests\DependencyInjection;

use PaymentSuite\RedsysBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $config = [
            'terminal' => '002',
            'url' => '/some-url',
            'merchant_code' => '1234567',
            'secret_key' => 'test-key',
            'payment_success' => [
                'route' => 'test-success',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
            'payment_failure' => [
                'route' => 'test-failure',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
        ];

        $normalized = $configTree->normalize($config);
        $finalized = $configTree->finalize($normalized);

        $expected = $config;
        $expected['settings_provider'] = 'default';

        $this->assertEquals($expected, $finalized);
    }

    public function testDefaultConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $config = [
            'merchant_code' => '1234567',
            'secret_key' => 'test-key',
            'payment_success' => [
                'route' => 'test-success',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
            'payment_failure' => [
                'route' => 'test-failure',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
        ];

        $normalized = $configTree->normalize($config);
        $finalized = $configTree->finalize($normalized);

        $expected = array_merge($config, [
            'terminal' => '001',
            'url' => 'https://sis.redsys.es/sis/realizarPago',
            'settings_provider' => 'default'
        ]);

        $this->assertEquals($expected, $finalized);
    }
}
