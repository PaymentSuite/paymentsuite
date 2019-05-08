<?php

namespace PaymentSuite\RedsysBundle\Tests;

use PaymentSuite\RedsysBundle\RedsysSignature;
use PHPUnit\Framework\TestCase;

class RedsysSignatureTest extends TestCase
{
    public function testToString()
    {
        $signature = new RedsysSignature('12345');

        $this->assertEquals('12345', $signature->__toString());
    }

    public function testMatch()
    {
        $signature = new RedsysSignature('12345');
        $signatureEquals = new RedsysSignature('12345');
        $signatureDifferent = new RedsysSignature('6789');

        $this->assertTrue($signature->match($signatureEquals));
        $this->assertFalse($signature->match($signatureDifferent));
    }
}
