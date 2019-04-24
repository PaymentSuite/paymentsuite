<?php

namespace PaymentSuite\StripeBundle\Tests\Services;

use PaymentSuite\StripeBundle\Services\StripeSettingsProviderDefault;
use PHPUnit\Framework\TestCase;

class StripeSettingsProviderDefaultTest extends TestCase
{
    public function testCreateService()
    {
        $privateKey = 'private';
        $publicKey = 'public';

        $service = new StripeSettingsProviderDefault($privateKey, $publicKey);

        $this->assertEquals($privateKey, $service->getPrivateKey());
        $this->assertEquals($publicKey, $service->getPublicKey());
        $this->assertEquals('Stripe', $service->getPaymentName());
    }
}
