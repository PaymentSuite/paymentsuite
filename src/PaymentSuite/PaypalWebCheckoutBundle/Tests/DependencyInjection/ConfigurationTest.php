<?php

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\DependencyInjection;

use PaymentSuite\PaypalWebCheckoutBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    public function testBusinessMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('business');

        $configTree->finalize($normalizedConfig);
    }

    public function testPaymentSuccessMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['business'] = 'dummy@paypal.com';
        unset($inputConfig['payment_success']);

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('payment_success');

        $configTree->finalize($normalizedConfig);
    }

    public function testPaymentCancelMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['business'] = 'dummy@paypal.com';
        unset($inputConfig['payment_cancel']);

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('payment_cancel');

        $configTree->finalize($normalizedConfig);
    }

    public function testBusinessMightNotBeSetIfCustomSettingsProviderIsSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['settings_provider'] = 'dummy_service_id';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $this->getDefaultConfiguration();
        $expectedConfig['settings_provider'] = 'dummy_service_id';
        $expectedConfig['business'] = 'dummy@paypal.com';

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function testDefaultConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['business'] = 'dummy@paypal.com';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $this->getDefaultConfiguration();
        $expectedConfig['business'] = 'dummy@paypal.com';

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
            'payment_cancel' => [
                'route' => 'test-route-failure',
                'order_append' => true,
                'order_append_field' => 'id',
            ],
        ];
    }

    private function getDefaultConfiguration()
    {
        $config = $this->getRoutesConfiguration();
        $config['settings_provider'] = 'default';
        $config['debug'] = true;
        $config['api_endpoint'] = 'https://www.paypal.com/cgi-bin/webscr';
        $config['sandbox_api_endpoint'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

        return $config;
    }
}
