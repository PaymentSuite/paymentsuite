<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\Services\RedsysSettingsProviderDefault;
use PHPUnit\Framework\TestCase;

class RedsysSettingsProviderDefaultTest extends TestCase
{
    public function testCreateService()
    {
        $merchantCode = '999888777';
        $terminal = '002';
        $secretKey = 'my-secret-and-secure-key';

        $service = new RedsysSettingsProviderDefault($merchantCode, $terminal, $secretKey);

        $this->assertNotNull($service);
        $this->assertEquals($merchantCode, $service->getMerchanCode());
        $this->assertEquals($terminal, $service->getTerminal());
        $this->assertEquals($secretKey, $service->getSecretKey());
        $this->assertEquals('redsys', $service->getPaymentName());
    }
}
