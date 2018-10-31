<?php

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use PaymentSuite\GestpayBundle\GestpayMethod;
use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Services\GestpayManager;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PHPUnit\Framework\TestCase;

class GestpayManagerTest extends TestCase
{
    public function testProcessPaymentResturnRedirUrl()
    {
        $paymentMethod = new GestpayMethod();

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

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal()
        );

        $gestpayManager->processPayment($paymentMethod);
    }

    public function testProcessPaymentThrowsExceptionIfNoOrderPresent()
    {
        $paymentMethod = new GestpayMethod();

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

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal()
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
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $paymentMethod = new GestpayMethod();
        $paymentMethod->setAmount(112.12);
        $paymentMethod->setShopTransactionId('123TXXXXXXX');
        $paymentMethod->setAuthorizationCode('33');
        $paymentMethod->setErrorCode(0);
        $paymentMethod->setErrorDescription('');
        $paymentMethod->setCurrency(242);
        $paymentMethod->setBankTransactionId('12');
        $paymentMethod->setTransactionResult('OK');

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->findOrder('123')
            ->shouldBeCalled()
            ->willReturn(true);
        $paymentBridge
            ->getAmount()
            ->shouldBeCalled()
            ->willReturn(112.12);

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

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal()
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
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $paymentMethod = new GestpayMethod();
        $paymentMethod->setAmount(112.12);
        $paymentMethod->setShopTransactionId('123TXXXXXXX');
        $paymentMethod->setAuthorizationCode('33');
        $paymentMethod->setErrorCode(0);
        $paymentMethod->setErrorDescription('');
        $paymentMethod->setCurrency(242);
        $paymentMethod->setBankTransactionId('12');
        $paymentMethod->setTransactionResult('KO');

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

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal()
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
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $paymentMethod = new GestpayMethod();
        $paymentMethod->setAmount(112.12);
        $paymentMethod->setShopTransactionId('123TXXXXXXX');
        $paymentMethod->setAuthorizationCode('33');
        $paymentMethod->setErrorCode(0);
        $paymentMethod->setErrorDescription('');
        $paymentMethod->setCurrency(242);
        $paymentMethod->setBankTransactionId('12');
        $paymentMethod->setTransactionResult('OK');

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

        $gestpayManager = new GestpayManager(
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $gestpayEncrypter->reveal()
        );

        $this->expectException(PaymentException::class);
        $gestpayManager->processResult($parameters);
    }
}
