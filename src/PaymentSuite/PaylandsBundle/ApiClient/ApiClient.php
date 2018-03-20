<?php

namespace PaymentSuite\PaylandsBundle\ApiClient;

use Http\Client\HttpClient;
use PaymentSuite\PaylandsBundle\Exception\ApiErrorException;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Client.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiClient implements ApiClientInterface
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var ApiRequestFactory
     */
    protected $apiRequestFactory;

    /**
     * @var bool
     */
    protected $sandbox;

    /**
     * @var string
     */
    protected $operative = ApiClientInterface::OPERATIVE_AUTHORIZATION;

    /**
     * @var array
     */
    protected $i18nTemplates = [];

    /**
     * @var string
     */
    protected $fallbackTemplate = '';

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * ApiClient constructor.
     *
     * @param HttpClient        $httpClient
     * @param ApiRequestFactory $apiRequestFactory
     * @param bool              $sandbox
     */
    public function __construct(HttpClient $httpClient, ApiRequestFactory $apiRequestFactory, $sandbox)
    {
        $this->httpClient = $httpClient;
        $this->apiRequestFactory = $apiRequestFactory;
        $this->sandbox = $sandbox;
    }

    /**
     * Gets if client is in sandbox mode.
     *
     * @return bool
     */
    public function isModeSandboxEnabled()
    {
        return $this->sandbox;
    }

    /**
     * Helper accessor to get current API service identifier.
     *
     * @return string
     */
    public function getCurrentService()
    {
        return $this->apiRequestFactory->getCurrentService();
    }

    /**
     * Helper accessor to get current API card validation service identifier.
     *
     * @return string
     */
    public function getCurrentValidationService()
    {
        return $this->apiRequestFactory->getCurrentValidationService();
    }

    /**
     * Sets API's payments operative.
     *
     * @param string $operative
     *
     * @return ApiClientInterface
     */
    public function setOperative($operative)
    {
        $this->operative = $operative;

        return $this;
    }

    /**
     * Gets current client's operative.
     *
     * @return string
     */
    public function getOperative()
    {
        return $this->operative;
    }

    /**
     * Sets defined template uuids to use to capture card by locale, and sets the fallback one.
     *
     * @param array $fallback
     * @param array $i18n
     */
    public function setTemplates($fallback, array $i18n)
    {
        $this->i18nTemplates = $i18n;
        $this->fallbackTemplate = $fallback;
    }

    /**
     * Gets current template uuid to use to capture card.
     *
     * @return string
     */
    public function getTemplate()
    {
        if (!$this->requestStack) {
            return $this->fallbackTemplate;
        }

        $currentLocale = $this->requestStack
            ->getCurrentRequest()
            ->getLocale();

        foreach ($this->i18nTemplates as $locale => $template) {
            if (strtolower($locale) === $currentLocale) {
                return $template;
            }
        }

        return $this->fallbackTemplate;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack = null)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Requests Paylands API to create a new payment order.
     *
     * @param string $customerExtId
     * @param int    $amount
     * @param string $description
     *
     * @return array
     */
    public function createPayment($customerExtId, $amount, $description)
    {
        $request = $this
            ->apiRequestFactory
            ->createPaymentRequest($customerExtId, $amount, $description, $this->getOperative());

        return $this->send($request);
    }

    /**
     * Requests Paylands API to create a new customer.
     *
     * @param int $customerExtId Customer external id to map to application
     *
     * @return array
     */
    public function createCustomer($customerExtId)
    {
        $request = $this
            ->apiRequestFactory
            ->createCustomerRequest($customerExtId);

        return $this->send($request);
    }

    /**
     * Requests Paylands API to retrieve tokenized cards of a customer.
     *
     * @param int $customerExtId Customer external id
     *
     * @return array
     */
    public function retrieveCustomerCards($customerExtId)
    {
        $request = $this
            ->apiRequestFactory
            ->createCustomerCardsRequest($customerExtId);

        return $this->send($request);
    }

    /**
     * Requests Paylands API to pay a previously created order.
     *
     * @param string $ip
     * @param string $orderUuid
     * @param string $cardUuid
     *
     * @return array
     */
    public function directPayment($ip, $orderUuid, $cardUuid)
    {
        $request = $this
            ->apiRequestFactory
            ->createDirectPaymentRequest($ip, $orderUuid, $cardUuid);

        return $this->send($request);
    }

    /**
     * Requests Paylands API to refund (totally or partially) a previously paid order.
     *
     * @param string $orderUuid
     * @param int    $amount
     *
     * @return array
     */
    public function refundPayment($orderUuid, $amount = null)
    {
        $request = $this
            ->apiRequestFactory
            ->createRefundPaymentRequest($orderUuid, $amount);

        return $this->send($request);
    }

    /**
     * Requests Paylands API to confirm a previously created 'deferred' order.
     *
     * @param string $orderUuid
     *
     * @return array
     */
    public function confirmPayment($orderUuid)
    {
        $request = $this
            ->apiRequestFactory
            ->createConfirmPaymentRequest($orderUuid);

        return $this->send($request);
    }

    /**
     * Requests Paylands API to cancel a previously created 'deferred' order.
     *
     * @param string $orderUuid
     *
     * @return array
     */
    public function cancelPayment($orderUuid)
    {
        $request = $this
            ->apiRequestFactory
            ->createCancelPaymentRequest($orderUuid);

        return $this->send($request);
    }

    /**
     * @param RequestInterface $request
     *
     * @return array
     *
     * @throws ApiErrorException
     */
    protected function send(RequestInterface $request)
    {
        try {
            $response = $this->httpClient->sendRequest($request);

            $body = (string) $response->getBody();

            return \json_decode($body, true);
        } catch (\Exception $e) {
            throw new ApiErrorException(sprintf('There was an error requesting Paylands API: %s', $e->getMessage()));
        }
    }
}
