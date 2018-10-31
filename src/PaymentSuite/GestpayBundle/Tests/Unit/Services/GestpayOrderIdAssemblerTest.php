<?php

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use PaymentSuite\GestpayBundle\Services\GestpayOrderIdAssembler;
use PHPUnit\Framework\TestCase;

class GestpayOrderIdAssemblerTest extends TestCase
{
    public function testAssemble()
    {
        $orderId = 128;

        $result = GestpayOrderIdAssembler::assemble($orderId);

        $this->assertContains('T', $result);
        $this->assertStringStartsWith('128', $result);
    }

    public function testExtract()
    {
        $shopTransactionId = '12345T1122334455';
        $result = GestpayOrderIdAssembler::extract($shopTransactionId);

        $this->assertEquals(12345, $result);
    }
}
