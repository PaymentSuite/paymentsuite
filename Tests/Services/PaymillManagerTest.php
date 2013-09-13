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

        $this->paymillManager = new PaymillManager($this->paymentEventDispatcher, $this->paymillTransactionWrapper, '', $this->cartWrapper, $this->orderWrapper);
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
            ->will($this->returnValue(10));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(5));

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
            ->will($this->returnValue(10));

        $this
            ->paymillMethod
            ->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue('789sad79sa7d9'));

        $this
            ->paymillTransactionWrapper
            ->expects($this->once())
            ->method($create)
            ->with($this->equalTo(array(
                'amount' => intval($cartAmount),
                'currency' => 'EUR',
                'token' => $paymentMethod->getApiToken(),
                'description' => $this->cartWrapper->getCartDescription()
            )))
            ->return($this->returnValue(array(


            )));

        $this
            ->cartWrapper
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(10));
    }
}