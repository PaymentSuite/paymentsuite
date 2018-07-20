<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\Services\RedsysOrderTransformer;
use PHPUnit\Framework\TestCase;

class RedsysOrderTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformer = new RedsysOrderTransformer();

        $order = $transformer->transform(1234);

        $this->assertEquals('1234T4957086', $order);
    }

    public function testReverseTransform()
    {
        $transformer = new RedsysOrderTransformer();

        $order = $transformer->reverseTransform('1234T4957086');

        $this->assertEquals(1234, $order);
    }
}

namespace PaymentSuite\RedsysBundle\Services;

function time()
{
    return 1556807594;
}
