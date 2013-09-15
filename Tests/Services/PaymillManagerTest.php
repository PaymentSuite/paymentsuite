<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymillBundle\Tests\Services;

use Mmoreram\PaymillBundle\Services\PaymillManager;

/**
 * Paymill manager
 */
class PaymillManagerTest extends \PHPUnit_Framework_TestCase
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
    private $paymillManager;


    /**
     * @var PaymentEventDispatcher
     * 
     * Paymetn event dispatcher object
     */
    private $paymentEventDispatcher;


    /**
     * @var PaymillTransactionWrapper
     * 
     * Wrapper for Paypall Transaction instance
     */
    private $paymillTransactionWrapper;


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
     * @var PaymillMethod class
     * 
     * Paymill Method object
     */
    private $paymillMethod;


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

        $this->paymillTransactionWrapper = $this
            ->getMockBuilder('Mmoreram\PaymillBundle\Services\Wrapper\PaymillTransactionWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymillMethod = $this
            ->getMockBuilder('Mmoreram\PaymillBundle\PaymillMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymillManager = new PaymillManager($this->paymentEventDispatcher, $this->paymillTransactionWrapper, '', $this->cartWrapper, $this->currencyWrapper, $this->orderWrapper);
    }


    /**
     * Testing different ammunts
     * 
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException
     */
    public function testDifferentAmounts()
    {
        $this
            ->paymillMethod
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

        $this->paymillManager->processPayment($this->paymillMethod);
    }


    /**
     * Testing payment error
     * 
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT * 100));

        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue(self::API_TOKEN));

        $this
            ->paymillMethod
            ->expects($this->any())
            ->method('setTransactionId');

        $this
            ->paymillMethod
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

        $this
            ->paymillTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo(array(
                'amount' => self::CART_AMOUNT * 100,
                'currency' => self::CURRENCY,
                'token' => self::API_TOKEN,
                'description' => self::CART_DESCRIPTION
            )))
            ->will($this->returnValue(array(
                'status'    =>  'something_different_to_closed',
                'id'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentReady')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentDone')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentFail')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentSuccess');

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderCreated');

        $this->paymillManager->processPayment($this->paymillMethod);
    }


    /**
     * Testing payment error
     * 
     */
    public function testPaymentSuccess()
    {
        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT * 100));

        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue(self::API_TOKEN));

        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->paymillMethod));

        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('setTransactionStatus')
            ->with($this->equalTo('closed'))
            ->will($this->returnValue($this->paymillMethod));

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

        $this
            ->paymillTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo(array(
                'amount' => self::CART_AMOUNT * 100,
                'currency' => self::CURRENCY,
                'token' => self::API_TOKEN,
                'description' => self::CART_DESCRIPTION
            )))
            ->will($this->returnValue(array(
                'status'    =>  'closed',
                'id'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentReady')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentDone')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentSuccess')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->cartWrapper), $this->equalTo($this->orderWrapper), $this->equalTo($this->paymillMethod));

        $this->paymillManager->processPayment($this->paymillMethod);
    }
}