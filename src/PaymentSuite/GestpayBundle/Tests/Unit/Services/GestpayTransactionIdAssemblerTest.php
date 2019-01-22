<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use PaymentSuite\GestpayBundle\Services\GestpayTransactionIdAssembler;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class GestpayTransactionIdAssemblerTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayTransactionIdAssemblerTest extends TestCase
{
    public function testAssemble()
    {
        $orderId = 128;

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrderId()
            ->shouldBeCalled()
            ->willReturn($orderId);

        $assembler = new GestpayTransactionIdAssembler($paymentBridge->reveal());
        $result = $assembler->assemble();

        $this->assertContains('T', $result);
        $this->assertStringStartsWith('128', $result);
    }

    public function testExtract()
    {
        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);

        $shopTransactionId = '12345T1122334455';
        $assembler = new GestpayTransactionIdAssembler($paymentBridge->reveal());
        $result = $assembler->extract($shopTransactionId);

        $this->assertEquals(12345, $result);
    }
}
