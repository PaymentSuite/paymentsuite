<?php

/**
 * BeFactory PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Mmoreram 2013
 */

namespace Mmoreram\PaymentCoreBundle\Tests\Event;

use Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent;

/**
 * Tests Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent class
 */
class PaymentOrderCreatedEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PaymentOrderCreatedEvent
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
     * @var OrderWrapper
     *
     * Order Wrapper
     */
    private $orderWrapper;


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

        $this->cartWrapper = $this->getMock('\Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface');
        $this->orderWrapper = $this->getMock('\Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface');
        $this->paymentMethod = $this->getMock('\Mmoreram\PaymentCoreBundle\PaymentMethodInterface');
        $this->event = new PaymentOrderCreatedEvent($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }


    /**
     * Testing if event instances Event Framework class
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $this->event);
    }


    /**
     * Testing getCartWrapper
     */
    public function testGetCartWrapper()
    {
        $this->assertEquals($this->cartWrapper, $this->event->getCartWrapper());
    }


    /**
     * Testing getOrderWrapper
     */
    public function testGetOrderWrapper()
    {
        $this->assertEquals($this->orderWrapper, $this->event->getOrderWrapper());
    }


    /**
     * Testing getPaymentMethod
     */
    public function testGetPaymentMethod()
    {
        $this->assertEquals($this->paymentMethod, $this->event->getPaymentMethod());
    }

}