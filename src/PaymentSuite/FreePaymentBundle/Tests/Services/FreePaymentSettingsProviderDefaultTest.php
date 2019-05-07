<?php

namespace PaymentSuite\FreePaymentBundle\Tests\Services;

use PaymentSuite\FreePaymentBundle\Services\FreePaymentSettingsProviderDefault;
use PHPUnit\Framework\TestCase;

class FreePaymentSettingsProviderDefaultTest extends TestCase
{
    public function testCreateService()
    {
        $service = new FreePaymentSettingsProviderDefault();

        $this->assertEquals('free_payment', $service->getPaymentName());
    }
}
