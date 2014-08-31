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

namespace PaymentSuite\PaymentCoreBundle\Tests\Event;

use PaymentSuite\PaymentCoreBundle\Event\PaymentOrderLoadEvent;

/**
 * Tests PaymentSuite\PaymentCoreBundle\Event\PaymentOrderLoadEvent class
 */
class PaymentOrderLoadEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaymentOrderLoadEvent
     *
     * Object to test
     */
    private $event;

    /**
     * @var PaymentBridge
     *
     * Order Wrapper
     */
    private $paymentBridge;

    /**
     * @var PaymentMethodInterface
     *
     * Payment method object
     */
    private $paymentMethod;

    /**
     * Setup
     */
    public function setUp()
    {

        $this->paymentBridge = $this->getMock('\PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface');
        $this->paymentMethod = $this->getMock('\PaymentSuite\PaymentCoreBundle\PaymentMethodInterface');
        $this->event = new PaymentOrderLoadEvent($this->paymentBridge, $this->paymentMethod);
    }

    /**
     * Testing if event instances Event Framework class
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $this->event);
    }

    /**
     * Testing getPaymentBridge
     */
    public function testGetPaymentBridge()
    {
        $this->assertEquals($this->paymentBridge, $this->event->getPaymentBridge());
    }

    /**
     * Testing getPaymentMethod
     */
    public function testGetPaymentMethod()
    {
        $this->assertEquals($this->paymentMethod, $this->event->getPaymentMethod());
    }
}
