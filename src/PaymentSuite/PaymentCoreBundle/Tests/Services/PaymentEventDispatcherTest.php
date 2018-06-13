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

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use PaymentSuite\PaymentCoreBundle\PaymentCoreEvents;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

/**
 * Tests PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher class.
 */
class PaymentEventDispatcherTest extends TestCase
{
    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var PaymentMethodInterface
     *
     * Payment method object
     */
    private $paymentMethod;

    /**
     * @var EventDispatcherInterface
     *
     * Event dispatcher
     */
    private $eventDispatcher;

    /**
     * Setup.
     */
    public function setUp()
    {
        $this->eventDispatcher = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentBridge = $this->getMockBuilder('\PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')->getMock();
        $this->paymentMethod = $this->getMockBuilder('\PaymentSuite\PaymentCoreBundle\PaymentMethodInterface')->getMock();
    }

    /**
     * Testing notifyPaymentOrderLoad.
     */
    public function testNotifyPaymentOrderLoad()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_LOAD),
                $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderLoadEvent')
            );

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderLoad(
            $this->paymentBridge,
            $this->paymentMethod
        );
    }

    /**
     * Testing notifyPaymentOrderCreated.
     */
    public function testNotifyPaymentOrderCreated()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(
                PaymentCoreEvents::PAYMENT_ORDER_CREATED),
                $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderCreatedEvent')
            );

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderCreated(
            $this->paymentBridge,
            $this->paymentMethod
        );
    }

    /**
     * Testing notifyPaymentDone.
     */
    public function testNotifyPaymentOrderDone()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_DONE),
                $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderDoneEvent')
            );

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderDone(
            $this->paymentBridge,
            $this->paymentMethod
        );
    }

    /**
     * Testing notifyPaymentSuccess.
     */
    public function testNotifyPaymentOrderSuccess()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_SUCCESS),
                $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderSuccessEvent')
            );

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderSuccess(
            $this->paymentBridge,
            $this->paymentMethod
        );
    }

    /**
     * Testing notifyPaymentFail.
     */
    public function testNotifyPaymentOrderFail()
    {
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(PaymentCoreEvents::PAYMENT_ORDER_FAIL),
                $this->isInstanceOf('PaymentSuite\PaymentCoreBundle\Event\PaymentOrderFailEvent')
            );

        $paymentEventDispatcher = new PaymentEventDispatcher($this->eventDispatcher);
        $paymentEventDispatcher->notifyPaymentOrderFail(
            $this->paymentBridge,
            $this->paymentMethod
        );
    }
}
