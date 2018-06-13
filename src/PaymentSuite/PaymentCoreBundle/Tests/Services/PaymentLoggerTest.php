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

namespace PaymentSuite\PaymentCoreBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;
use PHPUnit\Framework\TestCase;

/**
 * Tests PaymentSuite\PaymentCoreBundle\Services\PaymentLogger class.
 */
class PaymentLoggerTest extends TestCase
{
    /**
     * Tests log().
     */
    public function testLog()
    {
        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo('error'),
                $this->equalTo('[bundle] - message'),
                $this->equalTo([])
            );

        $paymentLogger = new PaymentLogger($logger, true);
        $paymentLogger->log(
            'error',
            'message',
            'bundle'
        );
    }

    /**
     * Tests log with false usage.
     */
    public function testDoNotLog()
    {
        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logger
            ->expects($this->never())
            ->method('log');

        $paymentLogger = new PaymentLogger($logger, false);
        $paymentLogger->log(
            'error',
            'message',
            'bundle'
        );
    }
}
