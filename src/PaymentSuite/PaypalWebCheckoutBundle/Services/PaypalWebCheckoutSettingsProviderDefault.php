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
    public function __construct($business)
    {
        $this->business = $business;
    }

    /**
     * {@inheritdoc}
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentName()
    {
        return 'paypal_web_checkout';
    }
}
