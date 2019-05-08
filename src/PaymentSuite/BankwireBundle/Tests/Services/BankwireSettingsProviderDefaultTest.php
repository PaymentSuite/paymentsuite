<?php

namespace PaymentSuite\BankwireBundle\Tests\Services;

use PaymentSuite\BankwireBundle\BankwireBundle;
use PaymentSuite\BankwireBundle\Services\BankwireSettingsProviderDefault;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutSettingsProviderDefault;
use PHPUnit\Framework\TestCase;

class BankwireSettingsProviderDefaultTest extends TestCase
{
    public function testCreateService()
    {
        $service = new BankwireSettingsProviderDefault();

        $this->assertNotNull($service);
        $this->assertEquals('Bankwire', $service->getPaymentName());
    }
}
