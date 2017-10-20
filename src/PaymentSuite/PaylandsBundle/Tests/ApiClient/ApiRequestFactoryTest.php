<?php

namespace PaymentSuite\PaylandsBundle\Tests\ApiCient;

use Http\Message\RequestFactory;
use PaymentSuite\PaylandsBundle\ApiClient\ApiDiscoveryProxy;
use PaymentSuite\PaylandsBundle\ApiClient\ApiRequestFactory;
use PaymentSuite\PaylandsBundle\ApiClient\ApiServiceResolver;
use Psr\Http\Message\RequestInterface;

/**
 * Class ApiRequestFactoryTest.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function trySetRequestFactory()
    {
        $apiRequestFactory = new ApiRequestFactoryTestClass(
            $this->prophesize(ApiServiceResolver::class)->reveal(),
            $this->prophesize(ApiDiscoveryProxy::class)->reveal(),
            'signature'
        );

        $requestFactoryMock = $this->prophesize(RequestFactory::class);

        $apiRequestFactory->setRequestFactory($requestFactoryMock->reveal());

        $this->assertSame($requestFactoryMock->reveal(), $apiRequestFactory->getRequestFactory());
    }

    /**
     * @test
     */
    public function trySetRequestFactoryWithDiscovery()
    {
        $requestFactoryMock = $this->prophesize(RequestFactory::class);

        $apiDiscoveryProxyMock = $this->prophesize(ApiDiscoveryProxy::class);
        $apiDiscoveryProxyMock
            ->discoverRequestFactory()
            ->shouldBeCalled()
            ->willReturn($requestFactoryMock->reveal());

        $apiRequestFactory = new ApiRequestFactoryTestClass(
            $this->prophesize(ApiServiceResolver::class)->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            'signature'
        );

        $apiRequestFactory->setRequestFactory();

        $this->assertSame($requestFactoryMock->reveal(), $apiRequestFactory->getRequestFactory());
    }

    /**
     * @test
     */
    public function tryCreatePaymentRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/payment',
            'body' => [
                'customer_ext_id' => '123A',
                'amount' => 15,
                'operative' => 'AUTHORIZATION',
                'service' => 'service-id',
                'description' => 'empty',
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock($data['body']['service']);

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createPaymentRequest(
                $data['body']['customer_ext_id'],
                $data['body']['amount'],
                $data['body']['description'],
                $data['body']['operative']
            );

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreateCustomerRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/customer',
            'body' => [
                'customer_ext_id' => '123A',
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createCustomerRequest($data['body']['customer_ext_id']);

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreateCustomerCardsRequest()
    {
        $data = [
            'method' => 'GET',
            'resource' => '/customer/123A/cards',
            'body' => [],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            'signature'
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createCustomerCardsRequest('123A');

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreateDirectPaymentRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/payment/direct',
            'body' => [
                'customer_ip' => '192.168.0.1',
                'order_uuid' => 'O-123',
                'card_uuid' => 'C-123',
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createDirectPaymentRequest(
                $data['body']['customer_ip'],
                $data['body']['order_uuid'],
                $data['body']['card_uuid']
            );

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreateCancelPaymentRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/payment/cancellation',
            'body' => [
                'order_uuid' => 'O-123',
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createCancelPaymentRequest($data['body']['order_uuid']);

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreateConfirmPaymentRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/payment/confirmation',
            'body' => [
                'order_uuid' => 'O-123',
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createConfirmPaymentRequest($data['body']['order_uuid']);

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreateTotalRefundPaymentRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/payment/refund',
            'body' => [
                'order_uuid' => 'O-123',
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createRefundPaymentRequest($data['body']['order_uuid']);

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @test
     */
    public function tryCreatePartialRefundPaymentRequest()
    {
        $data = [
            'method' => 'POST',
            'resource' => '/payment/refund',
            'body' => [
                'order_uuid' => 'O-123',
                'amount' => 15,
                'signature' => 'abcd',
            ],
        ];

        $requestMock = $this->prophesize(RequestInterface::class);

        $requestFactoryMock = $this->getRequestFactoryMock($requestMock->reveal(), $data);

        $apiDiscoveryProxyMock = $this->getApiDiscoveryProxyMock($requestFactoryMock->reveal());

        $apiServiceResolverMock = $this->getApiServiceResolverMock();

        $apiRequestFactory = new ApiRequestFactory(
            $apiServiceResolverMock->reveal(),
            $apiDiscoveryProxyMock->reveal(),
            $data['body']['signature']
        );

        $apiRequestFactory->setRequestFactory();

        $request = $apiRequestFactory
            ->setRequestFactory()
            ->createRefundPaymentRequest($data['body']['order_uuid'], $data['body']['amount']);

        $this->assertSame($request, $requestMock->reveal());
    }

    /**
     * @param $serviceId
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function getApiServiceResolverMock($serviceId = null)
    {
        $apiServiceResolverMock = $this->prophesize(ApiServiceResolver::class);

        if (!$serviceId) {
            return $apiServiceResolverMock;
        }

        $apiServiceResolverMock
            ->getService()
            ->shouldBeCalled()
            ->willReturn($serviceId);

        return $apiServiceResolverMock;
    }

    /**
     * @param RequestInterface $request
     * @param array            $data
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function getRequestFactoryMock(RequestInterface $request, array $data)
    {
        $requestFactoryMock = $this->prophesize(RequestFactory::class);
        $requestFactoryMock
            ->createRequest($data['method'], $data['resource'], [], empty($data['body']) ? null : \json_encode($data['body']))
            ->shouldBeCalled()
            ->willReturn($request);

        return $requestFactoryMock;
    }

    /**
     * @param RequestFactory $requestFactory
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function getApiDiscoveryProxyMock(RequestFactory $requestFactory)
    {
        $apiDiscoveryProxyMock = $this->prophesize(ApiDiscoveryProxy::class);
        $apiDiscoveryProxyMock
            ->discoverRequestFactory()
            ->shouldBeCalled()
            ->willReturn($requestFactory);

        return $apiDiscoveryProxyMock;
    }
}

/**
 * Class ApiRequestFactoryTestClass.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiRequestFactoryTestClass extends ApiRequestFactory
{
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }
}
