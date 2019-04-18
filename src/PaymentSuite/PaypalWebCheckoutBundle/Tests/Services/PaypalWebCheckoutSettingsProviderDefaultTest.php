<?php

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Services;

use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutSettingsProviderDefault;
use PHPUnit\Framework\TestCase;

class PaypalWebCheckoutSettingsProviderDefaultTest extends TestCase
{
    public function testCreateService()
    {
        $business = 'test@paypal.com';
        $service = new PaypalWebCheckoutSettingsProviderDefault($business);

        $this->assertNotNull($service);
        $this->assertEquals($business, $service->getBusiness());
        $this->assertEquals('paypal_web_checkout', $service->getPaymentName());
    }
}
