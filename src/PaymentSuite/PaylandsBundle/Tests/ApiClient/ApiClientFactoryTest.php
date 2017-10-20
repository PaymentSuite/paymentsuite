<?php

namespace PaymentSuite\PaylandsBundle\Tests\ApiCient;

use Http\Message\UriFactory;
use Http\Mock\Client;
use PaymentSuite\PaylandsBundle\ApiClient\ApiClientFactory;
use PaymentSuite\PaylandsBundle\ApiClient\ApiClientInterface;
use PaymentSuite\PaylandsBundle\ApiClient\ApiDiscoveryProxy;
use PaymentSuite\PaylandsBundle\ApiClient\ApiRequestFactory;
use Psr\Http\Message\UriInterface;

/**
 * Class ApiClientFactoryTest.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function trySetHttpClient()
    {
        $apiClientFactory = new ApiClientFactoryTestClass(
            $this->prophesize(ApiRequestFactory::class)->reveal(),
            $this->prophesize(ApiDiscoveryProxy::class)->reveal(),
            'api-key',
            'api-url',
            false
        );

        $httpClientMock = $this->prophesize(Client::class);

        $apiClientFactory->setHttpClient($httpClientMock->reveal());

        $this->assertSame($httpClientMock->reveal(), $apiClientFactory->getHttpClient());
    }

    /**
     * @test
     */
    public function trySetHttpClientWithDiscovery()
    {
        $httpClientMock = $this->prophesize(Client::class);

        $apiDiscoveryProxyMock = $this->prophesize(ApiDiscoveryProxy::class);
        $apiDiscoveryProxyMock
            ->discoverHttpClient()
            ->shouldBeCalled()
            ->willReturn($httpClientMock->reveal());

        $apiClientFactory = new ApiClientFactoryTestClass(
            $this->prophesize(ApiRequestFactory::class)->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            'api-key',
            'api-url',
            false
        );

        $apiClientFactory->setHttpClient();

        $this->assertSame($httpClientMock->reveal(), $apiClientFactory->getHttpClient());
    }

    /**
     * @test
     */
    public function trySetUriFactory()
    {
        $apiClientFactory = new ApiClientFactoryTestClass(
            $this->prophesize(ApiRequestFactory::class)->reveal(),
            $this->prophesize(ApiDiscoveryProxy::class)->reveal(),
            'api-key',
            'api-url',
            false
        );

        $uriFactoryMock = $this->prophesize(UriFactory::class);

        $apiClientFactory->setUriFactory($uriFactoryMock->reveal());

        $this->assertSame($uriFactoryMock->reveal(), $apiClientFactory->getUriFactory());
    }

    /**
     * @test
     */
    public function trySetUriFactoryWithDiscovery()
    {
        $uriFactoryMock = $this->prophesize(UriFactory::class);

        $apiDiscoveryProxyMock = $this->prophesize(ApiDiscoveryProxy::class);
        $apiDiscoveryProxyMock
            ->discoverUriFactory()
            ->shouldBeCalled()
            ->willReturn($uriFactoryMock->reveal());

        $apiClientFactory = new ApiClientFactoryTestClass(
            $this->prophesize(ApiRequestFactory::class)->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            'api-key',
            'api-url',
            false
        );

        $apiClientFactory->setUriFactory();

        $this->assertSame($uriFactoryMock->reveal(), $apiClientFactory->getUriFactory());
    }

    /**
     * @test
     */
    public function tryCreteApiClient()
    {
        $apiClientFactory = new ApiClientFactoryTestClass(
            $this->prophesize(ApiRequestFactory::class)->reveal(),
            $this->prophesize(ApiDiscoveryProxy::class)->reveal(),
            'api-key',
            'api-url',
            false
        );

        $uriFactoryMock = $this->prophesize(UriFactory::class);
        $uriFactoryMock
            ->createUri('api-url')
            ->shouldBeCalled()
            ->willReturn($this->prophesize(UriInterface::class)->reveal());

        $httpClientMock = $this->prophesize(Client::class);

        $apiClient = $apiClientFactory
            ->setUriFactory($uriFactoryMock->reveal())
            ->setHttpClient($httpClientMock->reveal())
            ->create();

        $this->assertInstanceOf(ApiClientInterface::class, $apiClient);
    }
}

/**
 * Class ApiClientFactoryTestClass.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiClientFactoryTestClass extends ApiClientFactory
{
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function getUriFactory()
    {
        return $this->uriFactory;
    }
}
