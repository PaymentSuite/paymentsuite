<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaymentCoreBundle\Tests\Services;

use Psr\Log\LoggerInterface;

use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;

/**
 * Tests PaymentSuite\PaymentCoreBundle\Services\PaymentLogger class
 */
class PaymentLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaymentLogger
     *
     * Payment logger
     */
    protected $paymentLogger;

    /**
     * @var LoggerInterface
     *
     * logger
     */
    protected $logger;

    /**
     * @var bool
     *
     * active
     */
    protected $active;

    /**
     * @var string
     *
     * level
     */
    protected $level;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->active = true;
        $this->level = 'info';

        $this->paymentLogger = new PaymentLogger($this->logger, $this->active, $this->level);
    }

    /**
     * Tests log()
     */
    public function testLog()
    {
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->will($this->returnValue(null))
        ;

        $this->paymentLogger->log('Test payment logger');
    }

    /**
     * Tests log with false usage
     */
    public function testDoNotLog()
    {
        $this->logger
            ->expects($this->never())
            ->method('log')
            ->will($this->returnValue(null))
        ;

        $this->paymentLogger->setActive(false);
        $this->paymentLogger->log('This should not be logged');
    }
}
