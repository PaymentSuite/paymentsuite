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

namespace PaymentSuite\GestBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use PaymentSuite\GestpayBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class ConfigurationTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class ConfigurationTest extends TestCase
{
    /**
     * @var array
     */
    public $defaults;

    /**
     * @var array
     */
    public $mandatory;

    public function testMandatoryConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);

        $configTree->finalize($normalizedConfig);
    }

    public function testDefaultConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['shop_login'] = 'test-login';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $inputConfig;
        $expectedConfig['api_key'] = null;
        $expectedConfig['sandbox'] = false;
        $expectedConfig['settings_provider'] = 'default';


        $this->assertSame($expectedConfig, $finalizedConfig);
    }

    public function testSandboxConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['shop_login'] = 'test-login';
        $inputConfig['sandbox'] = true;

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $inputConfig;
        $expectedConfig['api_key'] = null;
        $expectedConfig['settings_provider'] = 'default';

        $this->assertSame($expectedConfig, $finalizedConfig);
    }

    public function testApiKeyConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['shop_login'] = 'test-login';
        $inputConfig['sandbox'] = true;
        $inputConfig['api_key'] = 'some-api-key';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $inputConfig;
        $expectedConfig['settings_provider'] = 'default';

        $this->assertSame($expectedConfig, $finalizedConfig);
    }

    private function getRoutesConfiguration()
    {
        return [
            'payment_success' => [
                'route' => 'test-route-success',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
            'payment_failure' => [
                'route' => 'test-route-failure',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
        ];
    }
}
