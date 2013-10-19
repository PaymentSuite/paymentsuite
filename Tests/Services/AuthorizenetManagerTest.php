<?php

/**
 * AuthorizenetBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package AuthorizenetBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\AuthorizenetBundle\Tests\Services;

use dpcat237\AuthorizenetBundle\Services\AuthorizenetManager;

/**
 * Authorizenet manager
 */
class AuthorizenetManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var integer
     * 
     * Cart amount
     */
    const CART_AMOUNT = 1234;

    /**
     * @var integer
     *
     * Cart number
     */
    const CART_NUMBER = 4007000000027;

    /**
     * @var integer
     *
     * Cart expire month
     */
    const CART_EXPIRE_MONTH = 11;

    /**
     * @var integer
     *
     * Cart expire year
     */
    const CART_EXPIRE_YEAR = 17;

    /**
     * @var string
     * 
     * Cart description
     */
    const CART_DESCRIPTION = 'This is my cart description';

    /**
     * @var string
     *
     * Cart description
     */
    const LOGIN_ID = 'login_id';

    /**
     * @var string
     *
     * Cart description
     */
    const TRAN_KEY = 'tran_key';


    /**
     * @var PaymentManager
     * 
     * Payment manager object
     */
    private $authorizenetManager;


    /**
     * @var PaymentEventDispatcher
     * 
     * Paymetn event dispatcher object
     */
    private $paymentEventDispatcher;


    /**
     * @var AuthorizenetTransactionWrapper
     * 
     * Wrapper for Paypall Transaction instance
     */
    private $authorizenetTransactionWrapper;


    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge object
     */
    private $paymentBridgeInterface;


    /**
     * @var AuthorizenetMethod class
     * 
     * Authorizenet Method object
     */
    private $authorizenetMethod;


    /**
     * Setup method
     */
    public function setUp()
    {
        $this->paymentBridge = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizenetTransactionWrapper = $this
            ->getMockBuilder('dpcat237\AuthorizenetBundle\Services\Wrapper\AuthorizenetTransactionWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizenetMethod = $this
            ->getMockBuilder('dpcat237\AuthorizenetBundle\AuthorizenetMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizenetManager = new AuthorizenetManager($this->paymentEventDispatcher, $this->authorizenetTransactionWrapper, $this->paymentBridge, self::LOGIN_ID, self::TRAN_KEY);
    }


    /**
     * Testing payment error
     *
     * @expectedException \Mmoreram\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCartNumber')
            ->will($this->returnValue(self::CART_NUMBER));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationMonth')
            ->will($this->returnValue(self::CART_EXPIRE_MONTH));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationYear')
            ->will($this->returnValue(self::CART_EXPIRE_YEAR));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->authorizenetMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->authorizenetMethod));

        $this
            ->authorizenetMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('paid'))
            ->will($this->returnValue($this->authorizenetMethod));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(
                'order_description' =>  self::CART_DESCRIPTION
            )));

        $postValues = array(
            "x_login"			=> self::LOGIN_ID,
            "x_tran_key"		=> self::TRAN_KEY,

            "x_version"			=> "3.1",
            "x_delim_data"		=> "TRUE",
            "x_delim_char"		=> "|",
            "x_relay_response"	=> "FALSE",

            "x_type"			=> "AUTH_CAPTURE",
            "x_method"			=> "CC",
            "x_card_num"		=> self::CART_NUMBER,
            "x_exp_date"		=> self::CART_EXPIRE_MONTH.self::CART_EXPIRE_YEAR,

            "x_amount"			=> (float) number_format((self::CART_AMOUNT / 100), 2, '.', ''),
            "x_description"		=> self::CART_DESCRIPTION,
        );

        $postString = "";
        foreach( $postValues as $key => $value ) {
            $postString .= "$key=" . urlencode( $value ) . "&";
        }
        $postString = rtrim( $postString, "& " );

        $this
            ->authorizenetTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($postString)
            ->will($this->returnValue(array(
                '2'    =>  '0',
                '37'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderFail')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderSuccess');

        $this->authorizenetManager->processPayment($this->authorizenetMethod, self::CART_AMOUNT);
    }


    /**
     * Testing payment error
     *
     */
    public function testPaymentSuccess()
    {
        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCartNumber')
            ->will($this->returnValue(self::CART_NUMBER));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationMonth')
            ->will($this->returnValue(self::CART_EXPIRE_MONTH));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCartExpirationYear')
            ->will($this->returnValue(self::CART_EXPIRE_YEAR));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue(1));

        $this
            ->authorizenetMethod
            ->expects($this->any())
            ->method('setTransactionId')
            ->with($this->equalTo('123'))
            ->will($this->returnValue($this->authorizenetMethod));

        $this
            ->authorizenetMethod
            ->expects($this->any())
            ->method('setTransactionStatus')
            ->with($this->equalTo('paid'))
            ->will($this->returnValue($this->authorizenetMethod));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue(self::CART_AMOUNT));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(
                'order_description' =>  self::CART_DESCRIPTION
            )));

        $postValues = array(
            "x_login"			=> self::LOGIN_ID,
            "x_tran_key"		=> self::TRAN_KEY,

            "x_version"			=> "3.1",
            "x_delim_data"		=> "TRUE",
            "x_delim_char"		=> "|",
            "x_relay_response"	=> "FALSE",

            "x_type"			=> "AUTH_CAPTURE",
            "x_method"			=> "CC",
            "x_card_num"		=> self::CART_NUMBER,
            "x_exp_date"		=> self::CART_EXPIRE_MONTH.self::CART_EXPIRE_YEAR,

            "x_amount"			=> (float) number_format((self::CART_AMOUNT / 100), 2, '.', ''),
            "x_description"		=> self::CART_DESCRIPTION,
        );

        $postString = "";
        foreach( $postValues as $key => $value ) {
            $postString .= "$key=" . urlencode( $value ) . "&";
        }
        $postString = rtrim( $postString, "& " );

        $this
            ->authorizenetTransactionWrapper
            ->expects($this->once())
            ->method('create')
            ->with($postString)
            ->will($this->returnValue(array(
                '2'    =>  '1',
                '37'        =>  '123'
            )));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderCreated')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->any())
            ->method('notifyPaymentOrderFail');

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->authorizenetMethod));

        $this->authorizenetManager->processPayment($this->authorizenetMethod, self::CART_AMOUNT);
    }
}