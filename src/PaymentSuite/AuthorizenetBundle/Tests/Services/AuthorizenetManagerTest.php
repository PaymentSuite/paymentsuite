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

namespace PaymentSuite\AuthorizenetBundle\Tests\Services;

use PaymentSuite\AuthorizenetBundle\Services\AuthorizenetManager;

/**
 * Authorizenet manager
 */
class AuthorizenetManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var integer
     *
     * Card amount
     */
    const CARD_AMOUNT = 1234;

    /**
     * @var integer
     *
     * Card number
     */
    const CARD_NUMBER = 4007000000027;

    /**
     * @var integer
     *
     * Card expire month
     */
    const CARD_EXPIRE_MONTH = 11;

    /**
     * @var integer
     *
     * Card expire year
     */
    const CARD_EXPIRE_YEAR = 17;

    /**
     * @var string
     *
     * Card description
     */
    const CARD_DESCRIPTION = 'This is my card description';

    /**
     * @var string
     *
     * Card description
     */
    const LOGIN_ID = 'login_id';

    /**
     * @var string
     *
     * Card description
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
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizenetTransactionWrapper = $this
            ->getMockBuilder('PaymentSuite\AuthorizenetBundle\Services\Wrapper\AuthorizenetTransactionWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizenetMethod = $this
            ->getMockBuilder('PaymentSuite\AuthorizenetBundle\AuthorizenetMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizenetManager = new AuthorizenetManager($this->paymentEventDispatcher, $this->authorizenetTransactionWrapper, $this->paymentBridge, self::LOGIN_ID, self::TRAN_KEY);
    }

    /**
     * Testing payment error
     *
     * @expectedException \PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCardNumber')
            ->will($this->returnValue(self::CARD_NUMBER));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCardExpirationMonth')
            ->will($this->returnValue(self::CARD_EXPIRE_MONTH));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCardExpirationYear')
            ->will($this->returnValue(self::CARD_EXPIRE_YEAR));

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
            ->will($this->returnValue(self::CARD_AMOUNT));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(
                'order_description' =>  self::CARD_DESCRIPTION
            )));

        $postValues = array(
            "x_login"            => self::LOGIN_ID,
            "x_tran_key"        => self::TRAN_KEY,

            "x_version"            => "3.1",
            "x_delim_data"        => "TRUE",
            "x_delim_char"        => "|",
            "x_relay_response"    => "FALSE",

            "x_type"            => "AUTH_CAPTURE",
            "x_method"            => "CC",
            "x_card_num"        => self::CARD_NUMBER,
            "x_exp_date"        => self::CARD_EXPIRE_MONTH.self::CARD_EXPIRE_YEAR,

            "x_amount"            => (float) number_format((self::CARD_AMOUNT / 100), 2, '.', ''),
            "x_description"        => self::CARD_DESCRIPTION,
        );

        $postString = "";
        foreach ($postValues as $key => $value) {
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

        $this->authorizenetManager->processPayment($this->authorizenetMethod, self::CARD_AMOUNT);
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
            ->method('getCreditCardNumber')
            ->will($this->returnValue(self::CARD_NUMBER));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCardExpirationMonth')
            ->will($this->returnValue(self::CARD_EXPIRE_MONTH));

        $this
            ->authorizenetMethod
            ->expects($this->once())
            ->method('getCreditCardExpirationYear')
            ->will($this->returnValue(self::CARD_EXPIRE_YEAR));

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
            ->will($this->returnValue(self::CARD_AMOUNT));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(
                'order_description' =>  self::CARD_DESCRIPTION
            )));

        $postValues = array(
            "x_login"            => self::LOGIN_ID,
            "x_tran_key"        => self::TRAN_KEY,

            "x_version"            => "3.1",
            "x_delim_data"        => "TRUE",
            "x_delim_char"        => "|",
            "x_relay_response"    => "FALSE",

            "x_type"            => "AUTH_CAPTURE",
            "x_method"            => "CC",
            "x_card_num"        => self::CARD_NUMBER,
            "x_exp_date"        => self::CARD_EXPIRE_MONTH.self::CARD_EXPIRE_YEAR,

            "x_amount"            => (float) number_format((self::CARD_AMOUNT / 100), 2, '.', ''),
            "x_description"        => self::CARD_DESCRIPTION,
        );

        $postString = "";
        foreach ($postValues as $key => $value) {
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

        $this->authorizenetManager->processPayment($this->authorizenetMethod, self::CARD_AMOUNT);
    }
}
