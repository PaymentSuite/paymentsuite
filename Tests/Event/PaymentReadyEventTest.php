<?php

/**
 * BeFactory PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <marc.morera@befactory.com>
 * @package PaymentCoreBundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymentCoreBundle\Tests\Event;

use Befactory\PaymentCoreBundle\Event\PaymentReadyEvent;

/**
 * Tests Befactory\PaymentCoreBundle\Event\PaymentReadyEvent class
 */
class PaymentReadyEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PaymentReadyEvent
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

        $this->cartWrapper = $this->getMock('\Befactory\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface');
        $this->orderWrapper = $this->getMock('\Befactory\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface');
        $this->paymentMethod = $this->getMock('\Befactory\PaymentCoreBundle\PaymentMethodInterface');
        $this->event = new PaymentReadyEvent($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
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