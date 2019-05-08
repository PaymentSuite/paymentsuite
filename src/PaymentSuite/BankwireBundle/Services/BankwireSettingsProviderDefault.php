<?php

namespace PaymentSuite\BankwireBundle\Services;

use PaymentSuite\BankwireBundle\Services\Interfaces\BankwireSettingsProviderInterface;

/**
 * Class BankwireSettingsProviderDefault.
 */
class BankwireSettingsProviderDefault implements BankwireSettingsProviderInterface
{
    public function getPaymentName(): string
    {
        return 'Bankwire';
    }
}
