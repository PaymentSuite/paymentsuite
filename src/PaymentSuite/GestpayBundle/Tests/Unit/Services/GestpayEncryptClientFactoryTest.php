<?php

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use EndelWar\GestPayWS\WSCryptDecrypt;
use PaymentSuite\GestpayBundle\Services\GestpayEncryptClientFactory;
use PHPUnit\Framework\TestCase;

class GestpayEncryptClientFactoryTest extends TestCase
{
    public function testCreateSandbox()
    {
        $client = GestpayEncryptClientFactory::create(true);
        $this->assertInstanceOf(WSCryptDecrypt::class, $client);
    }

    public function testCreateProduction()
    {
        $client = GestpayEncryptClientFactory::create();
        $this->assertInstanceOf(WSCryptDecrypt::class, $client);
    }
}
