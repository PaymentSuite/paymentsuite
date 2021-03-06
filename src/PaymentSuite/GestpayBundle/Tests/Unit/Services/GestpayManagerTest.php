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

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use PaymentSuite\GestpayBundle\GestpayMethod;
use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Services\GestpayManager;
use PaymentSuite\GestpayBundle\Services\GestpayTransactionIdAssembler;
use PaymentSuite\GestpayBundle\Services\Interfaces\GestpaySettingsProviderInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * Class GestpayManagerTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayManagerTest extends TestCase
{
    public function testProcessPaymentReturnRedirUrl()
    {
        $paymentMethod = new GestpayMethod('test-name');

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

        $gestpayEncrypter = $this->prophesize(GestpayEncrypter::class);
        $gestpayEncrypter
            ->encryptedUrl()
            ->shouldBeCalled();

        $settingsProvider = $this->prophesize(GestpaySettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn('test-name');

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal(),
            new GestpayTransactionIdAssembler($paymentBridge->reveal()),
            $settingsProvider->reveal()
        );

        $gestpayManager->processPayment($paymentMethod);
    }

    public function testProcessPaymentThrowsExceptionIfNoOrderPresent()
    {
        $paymentMethod = new GestpayMethod('test-name');

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(false);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();

        $gestpayEncrypter = $this->prophesize(GestpayEncrypter::class);
        $gestpayEncrypter
            ->encryptedUrl()
            ->shouldNotBeCalled();

        $settingsProvider = $this->prophesize(GestpaySettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn('test-name');

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal(),
            new GestpayTransactionIdAssembler($paymentBridge->reveal()),
            $settingsProvider->reveal()
        );

        $this->expectException(PaymentOrderNotFoundException::class);

        $gestpayManager->processPayment($paymentMethod);
    }

    public function testProcessResultValidatesPayment()
    {
        $parameters = ['b' => 'test-string'];
        $decryptResponse = [
            'TransactionResult' => 'OK',
            'ShopTransactionID' => '123TXXXXXXX',
            'BankTransactionID' => '12',
            'AuthorizationCode' => '33',
            'Currency' => '242',
            'Amount' => '112.12',
            'CustomInfo' => '',
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $paymentMethod = new GestpayMethod('test-name');
        $paymentMethod->setAmount(112.12);
        $paymentMethod->setShopTransactionId('123TXXXXXXX');
        $paymentMethod->setAuthorizationCode('33');
        $paymentMethod->setErrorCode(0);
        $paymentMethod->setErrorDescription('');
        $paymentMethod->setCurrency(242);
        $paymentMethod->setBankTransactionId('12');
        $paymentMethod->setTransactionResult('OK');
        $paymentMethod->setCustomInfo('');

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->findOrder('123')
            ->shouldBeCalled()
            ->willReturn(true);
        $paymentBridge
            ->getAmount()
            ->shouldBeCalled()
            ->willReturn(11212);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();

        $gestpayEncrypter = $this->prophesize(GestpayEncrypter::class);
        $gestpayEncrypter
            ->decrypt($parameters['b'])
            ->shouldBeCalled()
            ->willReturn($decryptResponse);

        $settingsProvider = $this->prophesize(GestpaySettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn('test-name');

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal(),
            new GestpayTransactionIdAssembler($paymentBridge->reveal()),
            $settingsProvider->reveal()
        );

        $gestpayManager->processResult($parameters);
    }

    public function testProcessResultRejectsPayment()
    {
        $parameters = ['b' => 'test-string'];
        $decryptResponse = [
            'TransactionResult' => 'KO',
            'ShopTransactionID' => '123TXXXXXXX',
            'BankTransactionID' => '12',
            'AuthorizationCode' => '33',
            'Currency' => '242',
            'Amount' => '112.12',
            'CustomInfo' => '',
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $paymentMethod = new GestpayMethod('test-name');
        $paymentMethod->setAmount(112.12);
        $paymentMethod->setShopTransactionId('123TXXXXXXX');
        $paymentMethod->setAuthorizationCode('33');
        $paymentMethod->setErrorCode(0);
        $paymentMethod->setErrorDescription('');
        $paymentMethod->setCurrency(242);
        $paymentMethod->setBankTransactionId('12');
        $paymentMethod->setTransactionResult('KO');
        $paymentMethod->setCustomInfo('');

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->findOrder('123')
            ->shouldBeCalled()
            ->willReturn(true);
        $paymentBridge
            ->getAmount()
            ->shouldNotBeCalled();

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderFail($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();

        $gestpayEncrypter = $this->prophesize(GestpayEncrypter::class);
        $gestpayEncrypter
            ->decrypt($parameters['b'])
            ->shouldBeCalled()
            ->willReturn($decryptResponse);

        $settingsProvider = $this->prophesize(GestpaySettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn('test-name');

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal(),
            new GestpayTransactionIdAssembler($paymentBridge->reveal()),
            $settingsProvider->reveal()
        );

        $this->expectException(PaymentException::class);
        $gestpayManager->processResult($parameters);
    }

    public function testProcessResultRejectsPaymentIfNotMatchingAmount()
    {
        $parameters = ['b' => 'test-string'];
        $decryptResponse = [
            'TransactionResult' => 'OK',
            'ShopTransactionID' => '123TXXXXXXX',
            'BankTransactionID' => '12',
            'AuthorizationCode' => '33',
            'Currency' => '242',
            'Amount' => '112.12',
            'CustomInfo' => '',
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $paymentMethod = new GestpayMethod('test-name');
        $paymentMethod->setAmount(112.12);
        $paymentMethod->setShopTransactionId('123TXXXXXXX');
        $paymentMethod->setAuthorizationCode('33');
        $paymentMethod->setErrorCode(0);
        $paymentMethod->setErrorDescription('');
        $paymentMethod->setCurrency(242);
        $paymentMethod->setBankTransactionId('12');
        $paymentMethod->setTransactionResult('OK');
        $paymentMethod->setCustomInfo('');

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->findOrder('123')
            ->shouldBeCalled()
            ->willReturn(true);
        $paymentBridge
            ->getAmount()
            ->shouldBeCalled()
            ->willReturn(99)
        ;

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $paymentMethod)
            ->shouldNotBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderFail($paymentBridge->reveal(), $paymentMethod)
            ->shouldBeCalled();

        $gestpayEncrypter = $this->prophesize(GestpayEncrypter::class);
        $gestpayEncrypter
            ->decrypt($parameters['b'])
            ->shouldBeCalled()
            ->willReturn($decryptResponse);

        $settingsProvider = $this->prophesize(GestpaySettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->shouldBeCalled()
            ->willReturn('test-name');

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal(),
            new GestpayTransactionIdAssembler($paymentBridge->reveal()),
            $settingsProvider->reveal()
        );

        $this->expectException(PaymentException::class);
        $gestpayManager->processResult($parameters);
    }
}
