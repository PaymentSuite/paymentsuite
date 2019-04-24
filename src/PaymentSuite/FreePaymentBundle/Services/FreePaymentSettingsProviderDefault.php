<?php

namespace PaymentSuite\FreePaymentBundle\Services;

use PaymentSuite\FreePaymentBundle\Services\Interfaces\FreePaymentSettingsProviderInterface;

/**
 * Class FreePaymentSettingsProviderDefault.
 */
class FreePaymentSettingsProviderDefault implements FreePaymentSettingsProviderInterface
{
    public function getPaymentName(): string
    {
        return 'free_payment';
    }
}
