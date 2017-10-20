<?php

namespace PaymentSuite\PaylandsBundle\ApiClient;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Message\UriFactory;

/**
 * Class ApiDiscoveryProxy.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiDiscoveryProxy
{
    /**
     * @return HttpClient
     */
    public function discoverHttpClient()
    {
        return HttpClientDiscovery::find();
    }

    /**
     * @return MessageFactory
     */
    public function discoverRequestFactory()
    {
        return MessageFactoryDiscovery::find();
    }

    /**
     * @return UriFactory
     */
    public function discoverUriFactory()
    {
        return UriFactoryDiscovery::find();
    }
}
