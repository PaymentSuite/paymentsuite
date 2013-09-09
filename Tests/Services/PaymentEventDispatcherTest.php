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

namespace Mmoreram\PaymentCoreBundle\Tests\Services;

use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\PaymentCoreEvents;

/**
 * Tests Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher class
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

        $this->cartWrapper = $this->getMock('\Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface');
        $this->orderWrapper = $this->getMock('\Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface');
        $this->paymentMethod = $this->getMock('\Mmoreram\PaymentCoreBundle\PaymentMethodInterface');
    }


    /**
     * Testing notifyPaymentReady
     */
    public function testNotifyPaymentReady()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_READY), $this->isInstanceOf('Mmoreram\PaymentCoreBundle\Event\PaymentReadyEvent'));

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
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_DONE), $this->isInstanceOf('Mmoreram\PaymentCoreBundle\Event\PaymentDoneEvent'));

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
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_SUCCESS), $this->isInstanceOf('Mmoreram\PaymentCoreBundle\Event\PaymentSuccessEvent'));

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
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_FAIL), $this->isInstanceOf('Mmoreram\PaymentCoreBundle\Event\PaymentFailEvent'));

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
            ->with($this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_CREATED), $this->isInstanceOf('Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent'));

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderCreated($this->cartWrapper, $this->orderWrapper, $this->paymentMethod);
    }

}