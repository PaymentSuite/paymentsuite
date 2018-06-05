<?php

namespace PaymentSuite\PaylandsBundle\Tests\DependencyInjection;

use WAM\Paylands\ClientInterface;
use PaymentSuite\PaylandsBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    public $defaults;

    /**
     * @var array
     */
    public $mandatory;

    /**
     * @test
     * @dataProvider dataTestConfiguration
     *
     * @param mixed $inputConfig
     * @param mixed $expectedConfig
     */
    public function testConfiguration($inputConfig, $expectedConfig)
    {
        $configuration = new Configuration();

        $configTree = $configuration->getConfigTreeBuilder()->buildTree();

        $normalizedConfig = $configTree->normalize($inputConfig);
        $finalizedConfig = $configTree->finalize($normalizedConfig);

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function dataTestConfiguration()
    {
        $input1 = $this->getMandatoryConfiguration();
        $expectations1 = array_merge($this->getDefaultConfiguration(), $this->getMandatoryConfiguration());

        $i18nTemplates = [
            'i18n_template_uuids' => [
            'es' => 'es-uuid',
            'fr' => 'fr-uuid',
            'pt' => 'pt-uuid',
            ],
        ];

        $input2 = array_merge($input1, $i18nTemplates);
        $expectations2 = array_merge($expectations1, $i18nTemplates);

        return [
            'test default configuration' => [
                $input1, $expectations1,
            ],
            'test i18n templates configuration' => [
                $input2, $expectations2,
            ],
        ];
    }

    protected function getDefaultConfiguration()
    {
        return [
            'operative' => ClientInterface::OPERATIVE_AUTHORIZATION,
            'fallback_template_uuid' => 'default',
            'i18n_template_uuids' => [],
            'url' => 'https://ws-paylands.paynopain.com/v1/',
            'url_sandbox' => 'https://ws-paylands.paynopain.com/v1/sandbox/',
            'sandbox' => false,
            'templates' => [
                'view' => 'PaylandsBundle:Paylands:view.html.twig',
                'scripts' => 'PaylandsBundle:Paylands:scripts.html.twig',
            ],
            'interfaces' => [
                'http_client' => null,
                'request_factory' => null,
                'uri_factory' => null,
            ],
            'api_client' => Configuration::API_CLIENT_DEFAULT,
            'validation_service' => null,
        ];
    }

    protected function getMandatoryConfiguration()
    {
        return [
            'api_key' => 'test-key',
            'signature' => 'test-signature',
            'services' => [
                ['currency' => 'EUR', 'service' => 'test-service'],
            ],
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
