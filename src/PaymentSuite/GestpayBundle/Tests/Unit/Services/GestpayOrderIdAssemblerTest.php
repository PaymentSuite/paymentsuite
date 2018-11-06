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

use PaymentSuite\GestpayBundle\Services\GestpayOrderIdAssembler;
use PHPUnit\Framework\TestCase;

/**
 * Class GestpayOrderIdAssemblerTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
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
