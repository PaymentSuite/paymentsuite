<?php

namespace PaymentSuite\StripeBundle\Tests\DependencyInjection;

use PaymentSuite\StripeBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    public function testPublicKeyMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('public_key');

        $configTree->finalize($normalizedConfig);
    }

    public function testPrivateKeyMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['public_key'] = 'test_key';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('private_key');

        $configTree->finalize($normalizedConfig);
    }

    public function testPaymentSuccessMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['private_key'] = 'test_key';
        $inputConfig['public_key'] = 'test_key_2';
        unset($inputConfig['payment_success']);

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('payment_success');

        $configTree->finalize($normalizedConfig);
    }

    public function testPaymentFailureMustBeSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['private_key'] = 'test_key';
        $inputConfig['public_key'] = 'test_key_2';
        unset($inputConfig['payment_failure']);

        $normalizedConfig = $configTree->normalize($inputConfig);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('payment_failure');

        $configTree->finalize($normalizedConfig);
    }

    public function testMandatoryConfigMightNotBeSetIfCustomSettingsProviderIsSet()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['settings_provider'] = 'dummy_service_id';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $this->getDefaultConfiguration();
        $expectedConfig['settings_provider'] = 'dummy_service_id';

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function testDefaultConfiguration()
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $inputConfig = $this->getRoutesConfiguration();
        $inputConfig['private_key'] = 'private_dummy';
        $inputConfig['public_key'] = 'public_dummy';

        $normalizedConfig = $configTree->normalize($inputConfig);

        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $expectedConfig = $this->getDefaultConfiguration();

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
            'payment_failure' => [
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
        $config['api_endpoint'] = 'https://api.stripe.com/';
        $config['private_key'] = 'private_dummy';
        $config['public_key'] = 'public_dummy';
        $config['templates'] = [
            'view_template' => 'StripeBundle:Stripe:view.html.twig',
            'scripts_template' => 'StripeBundle:Stripe:scripts.html.twig',
        ];

        return $config;
    }
}
