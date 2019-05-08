<?php

namespace PaymentSuite\GestpayBundle\Services;

use PaymentSuite\GestpayBundle\Services\Interfaces\GestpaySettingsProviderInterface;

class GestpaySettingsProviderDefault implements GestpaySettingsProviderInterface
{
    /**
     * @var string
     */
    private $shopLogin;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * PaypalWebCheckoutSettingsProviderDefault constructor.
     *
     * @param string $shopLogin
     * @param string $apiKey
     */
    public function __construct(string $shopLogin, ?string $apiKey)
    {
        $this->shopLogin = $shopLogin;
        $this->apiKey = $apiKey;
    }

    public function getShopLogin(): string
    {
        return $this->shopLogin;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentName(): string
    {
        return 'gestpay';
    }
}
