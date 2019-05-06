<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysBundle\RedsysMethod;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysOrderTransformerInterface;
use PaymentSuite\RedsysBundle\Services\RedsysFormTypeBuilder;
use PaymentSuite\RedsysBundle\Services\RedsysManager;
use PaymentSuite\RedsysBundle\Services\RedsysMethodFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\Form;

class RedsysManagerTest extends TestCase
{
    public function testProcessPayment()
    {
        $form = $this->prophesize(Form::class);
        $redsysMethod = RedsysMethod::createEmpty('test-name');
        $order = new \stdClass();

        $redsysFormTypeBuilder = $this->prophesize(RedsysFormTypeBuilder::class);
        $redsysFormTypeBuilder
            ->buildForm()
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $redsysMethodFactory = $this->prophesize(RedsysMethodFactory::class);
        $redsysMethodFactory
            ->createEmpty()
            ->shouldBeCalled()
            ->willReturn($redsysMethod);

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn($order);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();

        $redsysOrderTransformer = $this->prophesize(RedsysOrderTransformerInterface::class);

        $manager = new RedsysManager(
            $redsysFormTypeBuilder->reveal(),
            $redsysMethodFactory->reveal(),
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $redsysOrderTransformer->reveal()
        );

        $builtForm = $manager->processPayment();

        $this->assertSame($form->reveal(), $builtForm);
    }

    public function testProcessPaymentThrowsExceptionIfNotAnOrder()
    {
        $redsysMethod = RedsysMethod::createEmpty('test-name');

        $redsysFormTypeBuilder = $this->prophesize(RedsysFormTypeBuilder::class);
        $redsysFormTypeBuilder
            ->buildForm()
            ->shouldNotBeCalled();

        $redsysMethodFactory = $this->prophesize(RedsysMethodFactory::class);
        $redsysMethodFactory
            ->createEmpty()
            ->shouldBeCalled()
            ->willReturn($redsysMethod);

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->getOrder()
            ->shouldBeCalled()
            ->willReturn(null);

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderLoad($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderCreated(Argument::cetera())
            ->shouldNotBeCalled();

        $redsysOrderTransformer = $this->prophesize(RedsysOrderTransformerInterface::class);

        $manager = new RedsysManager(
            $redsysFormTypeBuilder->reveal(),
            $redsysMethodFactory->reveal(),
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $redsysOrderTransformer->reveal()
        );

        $this->expectException(PaymentOrderNotFoundException::class);

        $manager->processPayment();
    }

    public function testProcessResult()
    {
        $order = '123';
        $redsysMethod = RedsysMethod::create('test-name', [
            'Ds_Order' => $order,
            'Ds_Response' => '0',
        ], '', '', '');
        $parameters = [];

        $redsysFormTypeBuilder = $this->prophesize(RedsysFormTypeBuilder::class);

        $redsysMethodFactory = $this->prophesize(RedsysMethodFactory::class);
        $redsysMethodFactory
            ->createFromResultParameters($parameters)
            ->shouldBeCalled()
            ->willReturn($redsysMethod);

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->findOrder((int) $order)
            ->shouldBeCalled();

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderSuccess($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();

        $redsysOrderTransformer = $this->prophesize(RedsysOrderTransformerInterface::class);
        $redsysOrderTransformer
            ->reverseTransform($order)
            ->shouldBeCalled()
            ->willReturn((int) $order);

        $manager = new RedsysManager(
            $redsysFormTypeBuilder->reveal(),
            $redsysMethodFactory->reveal(),
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $redsysOrderTransformer->reveal()
        );

        $manager->processResult($parameters);
    }

    public function testProcessResultThrowsExceptionIfNotTransactionSuccessful()
    {
        $order = '123';
        $redsysMethod = RedsysMethod::create('test-name', [
            'Ds_Order' => $order,
            'Ds_Response' => '100',
        ], '', '', '');
        $parameters = [];

        $redsysFormTypeBuilder = $this->prophesize(RedsysFormTypeBuilder::class);

        $redsysMethodFactory = $this->prophesize(RedsysMethodFactory::class);
        $redsysMethodFactory
            ->createFromResultParameters($parameters)
            ->shouldBeCalled()
            ->willReturn($redsysMethod);

        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);
        $paymentBridge
            ->findOrder((int) $order)
            ->shouldBeCalled();

        $paymentEventDispatcher = $this->prophesize(PaymentEventDispatcher::class);
        $paymentEventDispatcher
            ->notifyPaymentOrderDone($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();
        $paymentEventDispatcher
            ->notifyPaymentOrderFail($paymentBridge->reveal(), $redsysMethod)
            ->shouldBeCalled();

        $redsysOrderTransformer = $this->prophesize(RedsysOrderTransformerInterface::class);
        $redsysOrderTransformer
            ->reverseTransform($order)
            ->shouldBeCalled()
            ->willReturn((int) $order);

        $manager = new RedsysManager(
            $redsysFormTypeBuilder->reveal(),
            $redsysMethodFactory->reveal(),
            $paymentBridge->reveal(),
            $paymentEventDispatcher->reveal(),
            $redsysOrderTransformer->reveal()
        );

        $this->expectException(PaymentException::class);

        $manager->processResult($parameters);
    }
}
