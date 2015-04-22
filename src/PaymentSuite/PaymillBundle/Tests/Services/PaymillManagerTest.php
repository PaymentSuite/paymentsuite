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

namespace PaymentSuite\PaymillBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymillBundle\PaymillMethod;
use PaymentSuite\PaymillBundle\Services\PaymillManager;
use PaymentSuite\PaymillBundle\Services\Wrapper\PaymillTransactionWrapper;
use Paymill\Models\Response\Transaction;

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
     * Card amount
     */
    const ORDER_AMOUNT = 1000;

    /**
     * @var string
     *
     * Card description
     */
    const ORDER_DESCRIPTION = 'This is my card description';

    /**
     * @var PaymillManager
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
     * @var PaymentBridgeInterface
     *
     * Card Wrapper object
     */
    private $paymentBridge;

    /**
     * @var PaymillMethod class
     *
     * Paymill Method object
     */
    private $paymillMethod;

    /**
     * @var Transaction class
     *
     * Paymill Transaction response class
     */
    private $paymillResponseTransaction;

    /**
     * Setup method
     */
    public function setUp()
    {

        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymillTransactionWrapper = $this
            ->getMockBuilder('PaymentSuite\PaymillBundle\Services\Wrapper\PaymillTransactionWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymillMethod = $this
            ->getMockBuilder('PaymentSuite\PaymillBundle\PaymillMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymillResponseTransaction = $this
            ->getMockBuilder('Paymill\Models\Response\Transaction')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymillManager = new PaymillManager($this->paymentEventDispatcher, $this->paymillTransactionWrapper, $this->paymentBridge);
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
            ->will($this->returnValue(500));

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

        $this->paymillManager->processPayment($this->paymillMethod, self::ORDER_AMOUNT);
    }

    /**
     * Testing payment Error
     *
     * @expectedException \PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue(self::API_TOKEN));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->paymillMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->paymillMethod));

        $this
            ->paymillMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('closed'))
            ->will($this->returnValue($this->paymillMethod));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(self::CURRENCY));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::ORDER_AMOUNT));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(
                'order_description' =>  self::ORDER_DESCRIPTION
            )));

        $this->paymillResponseTransaction
            ->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('failed'));

        $this
            ->paymillTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo(self::ORDER_AMOUNT),
                $this->equalTo(self::CURRENCY),
                $this->equalTo(self::API_TOKEN),
                $this->equalTo(self::ORDER_DESCRIPTION)
            )
            ->will($this->returnValue($this->paymillResponseTransaction));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderFail')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderSuccess');

        $this->paymillManager->processPayment($this->paymillMethod, self::ORDER_AMOUNT);
    }

    /**
     * Testing payment Success
     */
    public function testPaymentSuccess()
    {
        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue(self::API_TOKEN));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->paymillMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->paymillMethod));

        $this
            ->paymillMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('closed'))
            ->will($this->returnValue($this->paymillMethod));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(self::CURRENCY));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::ORDER_AMOUNT));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(
                'order_description' =>  self::ORDER_DESCRIPTION
            )));

        $this->paymillResponseTransaction
            ->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('closed'));

        $this->paymillResponseTransaction
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(123));

        $this
            ->paymillTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo(self::ORDER_AMOUNT),
                $this->equalTo(self::CURRENCY),
                $this->equalTo(self::API_TOKEN),
                $this->equalTo(self::ORDER_DESCRIPTION)
            )
            ->will($this->returnValue($this->paymillResponseTransaction));

         $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->paymillMethod));

        $this->paymillManager->processPayment($this->paymillMethod, self::ORDER_AMOUNT);
    }
}
