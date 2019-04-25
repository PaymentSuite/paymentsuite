<?php

namespace PaymentSuite\GestpayBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

interface GestpaySettingsProviderInterface extends PaymentMethodInterface
{
    /**
     * Gets gestpay shop login.
     *
     * @return string
     */
    public function getShopLogin(): string;

    /**
     * Gest gestpay security api key.
     *
     * @return string
     */
    public function getApiKey(): ?string;
}
