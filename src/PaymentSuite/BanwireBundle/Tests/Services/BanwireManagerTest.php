<?php

/**
 * BanwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickaël Andrieu <mickael.andrieu@sensiolabs.com>
 * @package BanwireBundle
 *
 * Mickaël Andrieu 2014
 */

namespace PaymentSuite\BanwireBundle\Tests\Services;

use PaymentSuite\BanwireBundle\Services\BanwireManager;

class BanwireManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BankwireManager paymentManager
     *
     *  The manager to be unit tested
     */
    private $paymentManager;

    private $api;
    private $user;
    private $paymentBridge;
    private $paymentEventDispatcher;

    /**
     * @expectedException PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     */
    public function testProcessPaymentOk()
    {
        $amount = 100;

        $this->paymentBridge
            ->expects($this->exactly(2))
            ->method('getAmount')
            ->will($this->returnValue(1))
        ;

        $this->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(new \StdClass()))
        ;

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymentMethod))
            ->will($this->returnValue(null))
        ;

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymentMethod))
            ->will($this->returnValue(null))
        ;

        $this->paymentBridge
            ->expects($this->once())
            ->method('getOrderDescription')
            ->will($this->returnValue('Foo'))
        ;

        $this->paymentManager->processPayment($this->paymentMethod, $amount);
    }

    /**
     * @expectedException PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException
     */
    public function testProcessPaymentAmountsNotMatch()
    {
        $amount = 100;

        $this->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue($amount/($amount -1)))
        ;

        $this->paymentEventDispatcher
            ->expects($this->never())
            ->method($this->anything())
        ;

        $this->paymentManager->processPayment($this->paymentMethod, $amount);
    }

    /**
     * @expectedException PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException
     */
    public function testProcessPaymentOrderNotFound()
    {
        $amount = 100;

        $this->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(1))
        ;

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymentMethod))
            ->will($this->returnValue(null))
        ;

        $this->paymentManager->processPayment($this->paymentMethod, $amount);
    }

    protected function setUp()
    {
        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->setMethods(array('notifyPaymentOrderLoad', 'notifyPaymentOrderDone', 'notifyPaymentOrderSuccess', 'notifyPaymentOrderFail', 'notifyPaymentOrderCreated'))
            ->getMock()
        ;

        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->setMethods(array('setAmount', 'getAmount', 'findOrder', 'getOrder', 'setOrder', 'getOrderId', 'getCurrency', 'isOrderPaid', 'getExtraData', 'getOrderDescription'))
            ->getMock()
        ;

        $this->user = 'mmoreram';
        $this->api  = 'https://banwire.com/api.pago_pro';

        $this->paymentManager = new BanwireManager($this->paymentEventDispatcher, $this->paymentBridge, $this->user, $this->api);
        $this->paymentMethod  = $this
            ->getMockBuilder('PaymentSuite\BanwireBundle\BanwireMethod')
            ->setMethods(array('getAmount'))
            ->getMock()
        ;

    }

    protected function tearDown()
    {
        $this->paymentEventDispatcher = null;
        $this->paymentBridge          = null;
        $this->user                   = null;
        $this->api                    = null;
        $this->paymentManager         = null;
        $this->paymentMethod          = null;
    }
}
