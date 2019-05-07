<?php

namespace PaymentSuite\FreePaymentBundle\Tests\DependencyInjection;

use PaymentSuite\FreePaymentBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    public function testPaymentSuccessMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = [];

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('payment_success');

        $configTree->finalize($normalizedConfig);
    }

    public function testDefaultConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $this->getDefaultConfiguration();

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function testSettingProviderConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $settingsProvider = 'dummy.settings_provider.service';

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['settings_provider'] = $settingsProvider;

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $this->getDefaultConfiguration();
        $expectedConfig['settings_provider'] = $settingsProvider;

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    private function getRoutesConfiguration()
    {
        return [
            'payment_success' => [
                'route' => 'test-route-success',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
        ];
    }

    private function getDefaultConfiguration()
    {
        $config = $this->getRoutesConfiguration();
        $config['settings_provider'] = 'default';

        return $config;
    }
}
