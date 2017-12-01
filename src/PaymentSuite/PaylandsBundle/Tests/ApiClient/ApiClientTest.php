<?php

namespace PaymentSuite\PaylandsBundle\Tests\ApiCient;

use Http\Message\ResponseFactory;
use Http\Mock\Client;
use PaymentSuite\PaylandsBundle\ApiClient\ApiClient;
use PaymentSuite\PaylandsBundle\ApiClient\ApiRequestFactory;

/**
 * Class ApiClientTest.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function tryIsModeSandboxEnabled()
    {
        $httpClient = new Client($this->prophesize(ResponseFactory::class)->reveal());

        $apiRequestFactoryMock = $this->prophesize(ApiRequestFactory::class);

        $apiClient = new ApiClient($httpClient, $apiRequestFactoryMock->reveal(), true);

        $this->assertTrue($apiClient->isModeSandboxEnabled());

        $apiClient = new ApiClient($httpClient, $apiRequestFactoryMock->reveal(), false);

        $this->assertFalse($apiClient->isModeSandboxEnabled());
    }
}
