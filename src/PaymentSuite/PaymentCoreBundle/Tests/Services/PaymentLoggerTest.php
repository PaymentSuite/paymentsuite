<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
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
