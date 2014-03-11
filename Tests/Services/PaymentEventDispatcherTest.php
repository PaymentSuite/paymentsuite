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

namespace PaymentSuite\PaymentCoreBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymentCoreBundle\PaymentCoreEvents;

/**
 * Tests PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher class
 */
class PaymentEventDispatcherTest extends \PHPUnit_Framework_TestCase
{

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
     * @var EventDispatcher
     *
     * Event dispatcher
     */
    private $eventDispatcher;


    /**
     * Setup
     */
    public function setUp()
    {

        $this->eventDispatcher = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
            ->setMethods(array(
                'dispatch'
            ))
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentBridge = $this->getMock('\PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface');
        $this->paymentMethod = $this->getMock('\PaymentSuite\PaymentCoreBundle\PaymentMethodInterface');
    }


    /**
     * Testing notifyPaymentOrderLoad
     */
    public function testNotifyPaymentOrderLoad()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_LOAD), $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderLoadEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentOrderCreated
     */
    public function testNotifyPaymentOrderCreated()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_CREATED), $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderCreatedEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentDone
     */
    public function testNotifyPaymentOrderDone()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_DONE), $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderDoneEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentSuccess
     */
    public function testNotifyPaymentOrderSuccess()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_SUCCESS), $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderSuccessEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentFail
     */
    public function testNotifyPaymentOrderFail()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_FAIL), $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderFailEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $this->paymentMethod);
    }
}