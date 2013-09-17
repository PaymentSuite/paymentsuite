<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymentCoreBundle\Tests\Event;

use Mmoreram\PaymentCoreBundle\Event\PaymentOrderSuccessEvent;

/**
 * Tests Mmoreram\PaymentCoreBundle\Event\PaymentOrderSuccessEvent class
 */
class PaymentOrderSuccessEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PaymentOrderSuccessEvent
     *
     * Object to test
     */
    private $event;


    /**
     * @var CartWrapper
     *
     * Cart wrapper
     */
    private $cartWrapper;


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

        $this->paymentBridge = $this->getMock('\Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface');
        $this->paymentMethod = $this->getMock('\Mmoreram\PaymentCoreBundle\PaymentMethodInterface');
        $this->event = new PaymentOrderSuccessEvent($this->paymentBridge, $this->paymentMethod);
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