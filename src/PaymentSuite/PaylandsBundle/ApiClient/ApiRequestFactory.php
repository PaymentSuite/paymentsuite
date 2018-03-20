<?php

namespace PaymentSuite\PaylandsBundle\ApiClient;

use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;

/**
 * Class ApiRequestFactory.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiRequestFactory
{
    /**
     * @var string
     */
    protected $apiSignature;

    /**
     * @var ApiServiceResolver
     */
    protected $apiServiceResolver;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var ApiDiscoveryProxy
     */
    protected $apiDiscoveryProxy;

    /**
     * ApiRequestFactory constructor.
     *
     * @param string             $apiSignature
     * @param ApiServiceResolver $apiServiceResolver
     * @param ApiDiscoveryProxy  $apiDiscoveryProxy
     */
    public function __construct(
        ApiServiceResolver $apiServiceResolver,
        ApiDiscoveryProxy $apiDiscoveryProxy,
        $apiSignature
    ) {
        $this->apiServiceResolver = $apiServiceResolver;
        $this->apiDiscoveryProxy = $apiDiscoveryProxy;
        $this->apiSignature = $apiSignature;
    }

    /**
     * Sets factory for PSR-7 requests.
     *
     * @param RequestFactory $requestFactory
     *
     * @return $this
     */
    public function setRequestFactory(RequestFactory $requestFactory = null)
    {
        if (!$requestFactory) {
            $requestFactory = $this->apiDiscoveryProxy->discoverRequestFactory();
        }

        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * Returns a PSR-7 request to create a payment order into Paylands.
     *
     * @param $customerExtId
     * @param $amount
     * @param $description
     * @param $operative
     *
     * @return RequestInterface
     */
    public function createPaymentRequest($customerExtId, $amount, $description, $operative)
    {
        return $this->createRequest('POST', '/payment', [
            'customer_ext_id' => (string) $customerExtId,
            'amount' => $amount,
            'operative' => $operative,
            'service' => $this->getCurrentService(),
            'description' => $description,
        ]);
    }

    /**
     * Returns a PSR-7 request to create a customer into Paylands.
     *
     * @param $customerExtId
     *
     * @return RequestInterface
     */
    public function createCustomerRequest($customerExtId)
    {
        return $this->createRequest('POST', '/customer', [
            'customer_ext_id' => (string) $customerExtId,
        ]);
    }

    /**
     * Returns a PSR-7 request to retrieve customer's cards from Paylands.
     *
     * @param $customerExtId
     *
     * @return RequestInterface
     */
    public function createCustomerCardsRequest($customerExtId)
    {
        return $this->createRequest('GET', sprintf('/customer/%s/cards', $customerExtId));
    }

    /**
     * Returns a PSR-7 request to create a direct payment into Paylands.
     *
     * @param $ip
     * @param $orderUuid
     * @param $cardUuid
     *
     * @return RequestInterface
     */
    public function createDirectPaymentRequest($ip, $orderUuid, $cardUuid)
    {
        return $this->createRequest('POST', '/payment/direct', [
            'customer_ip' => $ip,
            'order_uuid' => $orderUuid,
            'card_uuid' => $cardUuid,
        ]);
    }

    /**
     * Returns a PSR-7 request to create a refund of a payment into Paylands.
     *
     * @param $orderUuid
     * @param null $amount
     *
     * @return RequestInterface
     */
    public function createRefundPaymentRequest($orderUuid, $amount = null)
    {
        $amountData = is_null($amount) ? [] : [
            'amount' => $amount,
        ];

        return $this->createRequest('POST', '/payment/refund', [
            'order_uuid' => $orderUuid,
        ] + $amountData);
    }

    /**
     * Returns a PSR-7 request to confirm a deferred payment into Paylands.
     *
     * @param $orderUuid
     *
     * @return RequestInterface
     */
    public function createConfirmPaymentRequest($orderUuid)
    {
        return $this->createRequest('POST', '/payment/confirmation', [
            'order_uuid' => $orderUuid,
        ]);
    }

    /**
     * Returns a PSR-7 request to cancel a deffered payment into Paylands.
     *
     * @param $orderUuid
     *
     * @return RequestInterface
     */
    public function createCancelPaymentRequest($orderUuid)
    {
        return $this->createRequest('POST', '/payment/cancellation', [
            'order_uuid' => $orderUuid,
        ]);
    }

    /**
     * Returns current API service identifier.
     *
     * @return string
     */
    public function getCurrentService()
    {
        return $this->apiServiceResolver->getService();
    }

    /**
     * Returns current API validation service identifier.
     *
     * @return string
     */
    public function getCurrentValidationService()
    {
        return $this->apiServiceResolver->getValidationService();
    }

    /**
     * Uses a PSR-7 compliant request factory to build up the expected request.
     *
     * @param string $method   Http method (POST, GET)
     * @param string $resource Uri path sufix
     * @param array  $data     Request's body data to send
     *
     * @return RequestInterface
     */
    private function createRequest($method, $resource, array $data = [])
    {
        if (!empty($data)) {
            $data['signature'] = $this->apiSignature;
        }

        return $this->requestFactory->createRequest($method, $resource, [], $this->encode($data));
    }

    /**
     * Encodes request body data as expected JSON.
     *
     * @param array $data
     *
     * @return string
     */
    private function encode(array $data)
    {
        if (empty($data)) {
            return null;
        }

        return \json_encode($data);
    }
}
