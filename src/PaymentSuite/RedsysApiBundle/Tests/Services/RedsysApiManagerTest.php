<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\RedsysApiBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysApiBundle\Services\RedsysApiManager;
use PaymentSuite\RedsysApiBundle\Services\Wrapper\RedsysApiTransactionWrapper;

/**
 * RedsysApi manager
 */
class RedsysApiManagerTest extends \PHPUnit_Framework_TestCase
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
     * @var RedsysApiManager
     *
     * Payment manager object
     */
    private $redsysApiManager;

    /**
     * @var PaymentEventDispatcher
     *
     * Paymetn event dispatcher object
     */
    private $paymentEventDispatcher;

    /**
     * @var RedsysApiTransactionWrapper
     *
     * Wrapper for Paypall Transaction instance
     */
    private $redsysApiTransactionWrapper;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge object
     */
    private $paymentBridge;

    /**
     * @var RedsysApiManager class
     *
     * RedsysApi Method object
     */
    private $redsysApiMethod;

    /**
     * Setup method
     */
    public function setUp()
    {
        $this->markTestSkipped();
        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->redsysApiTransactionWrapper = $this
            ->getMockBuilder('PaymentSuite\RedsysApiBundle\Services\Wrapper\RedsysApiTransactionWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->redsysApiMethod = $this
            ->getMockBuilder('PaymentSuite\RedsysApiBundle\RedsysApiMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->redsysApiManager = new RedsysApiManager($this->paymentEventDispatcher, $this->redsysApiTransactionWrapper, $this->paymentBridge);
    }

    /**
     * Testing different ammunts
     *
     * @expectedException \PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException
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

        $this->redsysApiManager->processPayment($this->redsysApiMethod, self::CART_AMOUNT);
    }

    /**
     * Testing payment error
     *
     * @expectedException \PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->redsysApiMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->redsysApiMethod));

        $this
            ->redsysApiMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('closed'))
            ->will($this->returnValue($this->redsysApiMethod));

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
            ->redsysApiTransactionWrapper
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
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderFail')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderSuccess');

        $this->redsysApiManager->processPayment($this->redsysApiMethod, self::CART_AMOUNT);
    }

    /**
     * Testing payment error
     *
     */
    public function testPaymentSuccess()
    {
        $this
            ->redsysApiMethod
            ->expects($this->once())
            ->method('getCreditCartNumber')
            ->will($this->returnValue(self::CART_NUMBER));

        $this
            ->redsysApiMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationMonth')
            ->will($this->returnValue(self::CART_EXPIRE_MONTH));

        $this
            ->redsysApiMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationYear')
            ->will($this->returnValue(self::CART_EXPIRE_YEAR));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->redsysApiMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->redsysApiMethod));

        $this
            ->redsysApiMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('paid'))
            ->will($this->returnValue($this->redsysApiMethod));

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
            ->redsysApiTransactionWrapper
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
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysApiMethod));

        $this->redsysApiManager->processPayment($this->redsysApiMethod, self::CART_AMOUNT);
    }
}
