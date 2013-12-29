<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package StripeBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\StripeBundle\Tests\Services;

use dpcat237\StripeBundle\Services\StripeManager;

/**
 * Stripe manager
 */
class StripeManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     * 
     * Currency
     */
    const CURRENCY = 'USD';

    /**
     * @var integer
     * 
     * Cart amount
     */
    const CART_AMOUNT = 50000;

    /**
     * @var integer
     *
     * Cart number
     */
    const CART_NUMBER = 4242424242424242;


    /**
     * @var integer
     *
     * Cart expire month
     */
    const CART_EXPIRE_MONTH = 12;


    /**
     * @var integer
     *
     * Cart expire year
     */
    const CART_EXPIRE_YEAR = 2017;


    /**
     * @var string
     * 
     * Cart description
     */
    const CART_DESCRIPTION = 'This is my cart description';


    /**
     * @var PaymentManager
     * 
     * Payment manager object
     */
    private $stripeManager;


    /**
     * @var PaymentEventDispatcher
     * 
     * Paymetn event dispatcher object
     */
    private $paymentEventDispatcher;


    /**
     * @var StripeTransactionWrapper
     * 
     * Wrapper for Paypall Transaction instance
     */
    private $stripeTransactionWrapper;


    /**
     * @var PaymentBridgeInterface
     * 
     * Payment bridge object
     */
    private $paymentBridgeInterface;


    /**
     * @var StripeMethod class
     * 
     * Stripe Method object
     */
    private $stripeMethod;


    /**
     * Setup method
     */
    public function setUp()
    {
        $this->paymentBridge = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->stripeTransactionWrapper = $this
            ->getMockBuilder('dpcat237\StripeBundle\Services\Wrapper\StripeTransactionWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->stripeMethod = $this
            ->getMockBuilder('dpcat237\StripeBundle\StripeMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->stripeManager = new StripeManager($this->paymentEventDispatcher, $this->stripeTransactionWrapper, $this->paymentBridge);
    }


    /**
     * Testing different ammunts
     *
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException
     */
    public function testDifferentAmounts()
    {
        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(2000));

        $this
            ->paymentBridge
            ->expects($this->any())
            ->method('getOrder');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderLoad');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderCreated');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderDone');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderSuccess');

        $this->stripeManager->processPayment($this->stripeMethod, self::CART_AMOUNT);
    }


    /**
     * Testing payment error
     *
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->stripeMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->stripeMethod));

        $this
            ->stripeMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('closed'))
            ->will($this->returnValue($this->stripeMethod));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(self::CURRENCY));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT));

        $cart = array(
            'number' => '',
            'exp_month' => '',
            'exp_year' => '',
        );

        $chargeParams = array(
            'card' => $cart,
            'amount' => self::CART_AMOUNT,
            'currency' => strtolower(self::CURRENCY),
        );

        $this
            ->stripeTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($chargeParams)
            ->will($this->returnValue(array(
                'paid'    =>  '0',
                'id'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderFail')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderSuccess');

        $this->stripeManager->processPayment($this->stripeMethod, self::CART_AMOUNT);
    }


    /**
     * Testing payment error
     *
     */
    public function testPaymentSuccess()
    {
        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getCreditCartNumber')
            ->will($this->returnValue(self::CART_NUMBER));

        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationMonth')
            ->will($this->returnValue(self::CART_EXPIRE_MONTH));

        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationYear')
            ->will($this->returnValue(self::CART_EXPIRE_YEAR));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->stripeMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->stripeMethod));

        $this
            ->stripeMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('paid'))
            ->will($this->returnValue($this->stripeMethod));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(self::CURRENCY));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT));

        $cart = array(
            'number' => self::CART_NUMBER,
            'exp_month' => self::CART_EXPIRE_MONTH,
            'exp_year' => self::CART_EXPIRE_YEAR,
        );

        $chargeParams = array(
            'card' => $cart,
            'amount' => self::CART_AMOUNT,
            'currency' => strtolower(self::CURRENCY),
        );

        $this
            ->stripeTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($chargeParams)
            ->will($this->returnValue(array(
                'paid'    =>  '1',
                'id'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->stripeMethod));

        $this->stripeManager->processPayment($this->stripeMethod, self::CART_AMOUNT);
    }
}