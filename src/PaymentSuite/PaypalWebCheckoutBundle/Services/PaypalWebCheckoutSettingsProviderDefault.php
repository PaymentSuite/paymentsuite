<?php

namespace PaymentSuite\PaypalWebCheckoutBundle\Services;

use PaymentSuite\PaypalWebCheckoutBundle\Services\Interfaces\PaypalWebCheckoutSettingsProviderInterface;

class PaypalWebCheckoutSettingsProviderDefault implements PaypalWebCheckoutSettingsProviderInterface
{
    /**
     * @var string
     */
    private $business;

    /**
     * PaypalWebCheckoutSettingsProviderDefault constructor.
     *
     * @param string $business
     */
    public function __construct(string $business)
    {
        $this->business = $business;
    }

    /**
     * {@inheritdoc}
     */
    public function getBusiness(): string
    {
        return $this->business;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentName(): string
    {
        return 'paypal_web_checkout';
    }
}
