<?php

namespace PaymentSuite\PaypalWebCheckoutBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

interface PaypalWebCheckoutSettingsProviderInterface extends PaymentMethodInterface
{
    /**
     * Gets paypal business account email.
     *
     * @return string
     */
    public function getBusiness(): string;
}
