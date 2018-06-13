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

namespace PaymentSuite\PaylandsBundle\Tests\Services;

use PaymentSuite\PaylandsBundle\Exception\CardInvalidException;
use PaymentSuite\PaylandsBundle\Exception\CardNotFoundException;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaylandsBundle\Services\PaylandsApiAdapter;
use PaymentSuite\PaylandsBundle\Services\PaylandsEventDispatcher;
use PaymentSuite\PaylandsBundle\Services\PaylandsManager;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * Class PaylandsManagerTest
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsManagerTest extends TestCase
{
    /**
     * @test
     */
    public function paymentWorksFineAndCreatesAPaymentTransaction()
    {
        $paymentMethod = new PaylandsMethod();

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(true);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();

        $paylandsEventDispatcher = $this->prophesize(PaylandsEventDispatcher::class);
        $paylandsEventDispatcher
            ->notifyCardValid($paymentMethod)
            ->shouldBeCalled();

        $paylandsApiAdapter = $this->prophesize(PaylandsApiAdapter::class);
        $paylandsApiAdapter
            ->validateCard($paymentMethod)
            ->shouldBeCalled()
            ->will(function ($args) {
                /** @var PaylandsMethod $paymentMethod */
                $paymentMethod = $args[0];
                $paymentMethod->setPaymentStatus(PaylandsMethod::STATUS_OK);
                $paymentMethod->setPaymentResult([]);
            });
        $paylandsApiAdapter
            ->createTransaction($paymentMethod)
            ->shouldBeCalled();

        $paylandsManager = new PaylandsManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $paylandsEventDispatcher->reveal(),
            $paylandsApiAdapter->reveal()
        );

        $paylandsManager->processPayment($paymentMethod);
    }

    /**
     * @test
     */
    public function paymentWorksFineOnlyCardValidation()
    {
        $paymentMethod = new PaylandsMethod();

        $paymentMethod->setOnlyTokenizeCard(true);

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(true);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();

        $paylandsEventDispatcher = $this->prophesize(PaylandsEventDispatcher::class);
        $paylandsEventDispatcher
            ->notifyCardValid($paymentMethod)
            ->shouldBeCalled();

        $paylandsApiAdapter = $this->prophesize(PaylandsApiAdapter::class);
        $paylandsApiAdapter
            ->validateCard($paymentMethod)
            ->shouldBeCalled()
            ->will(function ($args) {
                /** @var PaylandsMethod $paymentMethod */
                $paymentMethod = $args[0];
                $paymentMethod->setPaymentStatus(PaylandsMethod::STATUS_OK);
                $paymentMethod->setPaymentResult([]);

            });
        $paylandsApiAdapter
            ->createTransaction($paymentMethod)
            ->shouldNotBeCalled();

        $paylandsManager = new PaylandsManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $paylandsEventDispatcher->reveal(),
            $paylandsApiAdapter->reveal()
        );

        $paylandsManager->processPayment($paymentMethod);
    }

    /**
     * @test
     */
    public function paymentThrowsExceptionIfCardNotValid()
    {
        $paymentMethod = new PaylandsMethod();

        $paymentMethod->setOnlyTokenizeCard(true);

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(true);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderFail($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();

        $paylandsEventDispatcher = $this->prophesize(PaylandsEventDispatcher::class);
        $paylandsEventDispatcher
            ->notifyCardValid($paymentMethod)
            ->shouldNotBeCalled();

        $paylandsApiAdapter = $this->prophesize(PaylandsApiAdapter::class);
        $paylandsApiAdapter
            ->validateCard($paymentMethod)
            ->shouldBeCalled()
            ->willThrow(new CardNotFoundException());

        $paylandsApiAdapter
            ->createTransaction($paymentMethod)
            ->shouldNotBeCalled();

        $paylandsManager = new PaylandsManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $paylandsEventDispatcher->reveal(),
            $paylandsApiAdapter->reveal()
        );

        $this->expectException(CardNotFoundException::class);

        $paylandsManager->processPayment($paymentMethod);
    }

    /**
     * @test
     */
    public function paymentThrowsExceptionIfCardValidEventThrowsException()
    {
        $paymentMethod = new PaylandsMethod();

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(true);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderFail($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();

        $paylandsEventDispatcher = $this->prophesize(PaylandsEventDispatcher::class);
        $paylandsEventDispatcher
            ->notifyCardValid($paymentMethod)
            ->shouldBeCalled()
            ->willThrow(CardInvalidException::class);

        $paylandsApiAdapter = $this->prophesize(PaylandsApiAdapter::class);
        $paylandsApiAdapter
            ->validateCard($paymentMethod)
            ->shouldBeCalled();

        $paylandsApiAdapter
            ->createTransaction($paymentMethod)
            ->shouldNotBeCalled();

        $paylandsManager = new PaylandsManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $paylandsEventDispatcher->reveal(),
            $paylandsApiAdapter->reveal()
        );

        $this->expectException(CardInvalidException::class);

        $paylandsManager->processPayment($paymentMethod);
    }

    /**
     * @test
     */
    public function paymentThrowsExceptionIfTransactionFails()
    {
        $paymentMethod = new PaylandsMethod();

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(true);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderFail($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();

        $paylandsEventDispatcher = $this->prophesize(PaylandsEventDispatcher::class);
        $paylandsEventDispatcher
            ->notifyCardValid($paymentMethod)
            ->shouldBeCalled();

        $paylandsApiAdapter = $this->prophesize(PaylandsApiAdapter::class);
        $paylandsApiAdapter
            ->validateCard($paymentMethod)
            ->shouldBeCalled();

        $paylandsApiAdapter
            ->createTransaction($paymentMethod)
            ->shouldBeCalled(function ($args) {
                /** @var PaylandsMethod $paymentMethod */
                $paymentMethod = $args[0];
                $paymentMethod->setPaymentStatus(PaylandsMethod::STATUS_KO);
                $paymentMethod->setPaymentResult([]);
            });

        $paylandsManager = new PaylandsManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $paylandsEventDispatcher->reveal(),
            $paylandsApiAdapter->reveal()
        );

        $this->expectException(PaymentException::class);

        $paylandsManager->processPayment($paymentMethod);
    }
}
