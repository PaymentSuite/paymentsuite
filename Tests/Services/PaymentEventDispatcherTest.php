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

namespace Befactory\PaymentCoreBundle\Tests\Services;

use Befactory\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Befactory\PaymentCoreBundle\PaymentCoreEvents;

/**
 * Tests Befactory\PaymentCoreBundle\Services\PaymentEventDispatcher class
 */
class PaymentEventDispatcherTest extends \PHPUnit_Framework_TestCase
{

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

        $this->cartWrapper = $this->getMock('\Befactory\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface');
        $this->orderWrapper = $this->getMock('\Befactory\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface');
        $this->paymentMethod = $this->getMock('\Befactory\PaymentCoreBundle\PaymentMethodInterface');
    }


    /**
     * Testing notifyPaymentReady
     */
    public function testNotifyPaymentReady()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_READY), $this->isInstanceOf('Befactory\PaymentCoreBundle\Event\PaymentReadyEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentReady($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentDone
     */
    public function testNotifyPaymentDone()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_DONE), $this->isInstanceOf('Befactory\PaymentCoreBundle\Event\PaymentDoneEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentDone($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentSuccess
     */
    public function testNotifyPaymentSuccess()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_SUCCESS), $this->isInstanceOf('Befactory\PaymentCoreBundle\Event\PaymentSuccessEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentSuccess($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentFail
     */
    public function testNotifyPaymentFail()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_FAIL), $this->isInstanceOf('Befactory\PaymentCoreBundle\Event\PaymentFailEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentFail($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }


    /**
     * Testing notifyPaymentOrderCreated
     */
    public function testNotifyPaymentOrderCreated()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_CREATED), $this->isInstanceOf('Befactory\PaymentCoreBundle\Event\PaymentOrderCreatedEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderCreated($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }

}