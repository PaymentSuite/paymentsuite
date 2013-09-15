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
    const CURRENCY = 'EUR';

    /**
     * @var string
     * 
     * Currency
     */
    const API_TOKEN = '2374932748923';


    /**
     * @var integer
     * 
     * Cart amount
     */
    const CART_AMOUNT = 10;

    /**
     * @var integer
     *
     * Cart amount
     */
    const CART_NUMBER = 4242424242424242;


    /**
     * @var integer
     *
     * Cart amount
     */
    const CART_EXPIRE_MONTH = 12;


    /**
     * @var integer
     *
     * Cart amount
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
     * @var CartWrapper
     * 
     * Cart Wrapper object
     */
    private $cartWrapper;


    /**
     * @var CurrencyWrapper
     * 
     * Currency Wrapper
     */
    private $currencyWrapper;


    /**
     * @var OrderWrapper
     * 
     * Order Wrapper object
     */
    private $orderWrapper;


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

        $this->cartWrapper = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyWrapper = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\Wrapper\CurrencyWrapper')
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrency'))
            ->getMock();

        $this->orderWrapper = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface')
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

        $this->stripeManager = new StripeManager($this->paymentEventDispatcher, $this->stripeTransactionWrapper, '', $this->cartWrapper, $this->currencyWrapper, $this->orderWrapper);
    }


    /**
     * Testing different ammunts
     * 
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException
     */
    public function testDifferentAmounts()
    {
        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT * 100));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(500));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentReady');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentDone');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentSuccess');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderCreated');

        $this->stripeManager->processPayment($this->stripeMethod);
    }


    /**
     * Testing payment error
     * 
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT * 100));

        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue(self::API_TOKEN));

        $this
            ->stripeMethod
            ->expects($this->any())
            ->method('setTransactionId');

        $this
            ->stripeMethod
            ->expects($this->any())
            ->method('setTransactionStatus');

        $this
            ->currencyWrapper
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(self::CURRENCY));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getCartDescription')
            ->will($this->returnValue(self::CART_DESCRIPTION));

        $cart = array(
            'number' => self::CART_NUMBER,
            'exp_month' => self::CART_EXPIRE_MONTH,
            'exp_year' => self::CART_EXPIRE_YEAR,
        );

        $this
            ->stripeTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo(array(
                'card' => $cart,
                'amount' => self::CART_AMOUNT * 100,
                'currency' => strtolower(self::CURRENCY),
            )))
            ->will($this->returnValue(array(
                'paid'    =>  '0',
                'id'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentReady')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentDone')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentFail')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentSuccess');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderCreated');

        $this->stripeManager->processPayment($this->stripeMethod);
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
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT * 100));

        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue(self::API_TOKEN));

        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->stripeMethod));

        $this
            ->stripeMethod
            ->expects($this->once())
            ->method('setTransactionStatus')
            ->with($this->equalTo('closed'))
            ->will($this->returnValue($this->stripeMethod));

        $this
            ->currencyWrapper
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(self::CURRENCY));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getCartDescription')
            ->will($this->returnValue(self::CART_DESCRIPTION));

        $cart = array(
            'number' => self::CART_NUMBER,
            'exp_month' => self::CART_EXPIRE_MONTH,
            'exp_year' => self::CART_EXPIRE_YEAR,
        );

        $this
            ->stripeTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo(array(
                'card' => $cart,
                'amount' => self::CART_AMOUNT * 100,
                'currency' => strtolower(self::CURRENCY),
            )))
            ->will($this->returnValue(array(
                'paid'    =>  '1',
                'id'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentReady')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentDone')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentSuccess')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->stripeMethod));

        $this->stripeManager->processPayment($this->stripeMethod);
    }
}