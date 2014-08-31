<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\DineroMailBundle\Tests\Services;

use PaymentSuite\DineromailBundle\DineromailMethod;
use PaymentSuite\DineroMailBundle\Services\DineromailManager;

class DineromailManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DineromailManager paymentManager
     *
     * The manager to be unit tested
     */
    private $paymentManager;

    private $countryId;
    private $merchantId;
    private $merchantPwd;
    private $paymentEventDispatcher;
    private $paymentBridge;
    private $paymentMethod;
    private $logger;

    public function testProcessTransactionPended()
    {
        $this->logger
            ->expects($this->once())
            ->method('addInfo')
            ->will($this->returnValue(null))
        ;

        $xml = simplexml_load_file(__DIR__ . '/../Fixtures/pending_report.xml');
        $xmlTransaction = $xml->DETALLE->OPERACIONES->OPERACION;

        $this->paymentMethod->setDineromailTransactionId($xmlTransaction->ID);
        $this->paymentMethod->setAmount($xmlTransaction->MONTO);

        $this->paymentEventDispatcher
            ->expects($this->never())
            ->method($this->anything())
        ;

        $this->paymentManager->processTransaction($xmlTransaction);
    }

    public function testProcessTransactionAccepted()
    {
        $this->logger
            ->expects($this->once())
            ->method('addInfo')
            ->will($this->returnValue(null))
        ;

        $xml = simplexml_load_file(__DIR__ . '/../Fixtures/success_report.xml');
        $xmlTransaction = $xml->DETALLE->OPERACIONES->OPERACION;

        $this->paymentMethod->setDineromailTransactionId($xmlTransaction->ID);
        $this->paymentMethod->setAmount($xmlTransaction->MONTO);

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->paymentBridge, $this->paymentMethod)
            ->will($this->returnValue(null))
        ;

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->paymentBridge, $this->paymentMethod)
            ->will($this->returnValue(null))
        ;

        $this->paymentManager->processTransaction($xmlTransaction);
    }

    public function testProcessTransationDenied()
    {
        $this->logger
            ->expects($this->once())
            ->method('addInfo')
            ->will($this->returnValue(null))
        ;

        $xml = simplexml_load_file(__DIR__ . '/../Fixtures/deny_report.xml');
        $xmlTransaction = $xml->DETALLE->OPERACIONES->OPERACION;

        $this->paymentMethod->setDineromailTransactionId($xmlTransaction->ID);
        $this->paymentMethod->setAmount($xmlTransaction->MONTO);

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderLoad')
            ->with($this->paymentBridge, $this->paymentMethod)
            ->will($this->returnValue(null))
        ;

        $this->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderFail')
            ->with($this->paymentBridge, $this->paymentMethod)
            ->will($this->returnValue(null))
        ;

        $this->paymentManager->processTransaction($xmlTransaction);
    }

    protected function setUp()
    {
        $this->countryId = 1; // Argentina
        $this->merchantId ="LF5D21Y0JACZ45H5Y5GEDG68";
        $this->merchantPwd = "31A9BC03-64D0-4A81-AD5D-E4CB9AAE17FC";

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->setMethods(array('notifyPaymentOrderLoad', 'notifyPaymentOrderSuccess', 'notifyPaymentOrderFail'))
            ->getMock()
        ;

        $this->paymentBridge = $this->getMock('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface');
        $this->paymentMethod = new DineromailMethod();

        $this->logger = $this
            ->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->setMethods(array('addInfo'))
            ->getMock()
        ;

        $this->paymentManager = new DineromailManager($this->paymentEventDispatcher, $this->paymentBridge, $this->countryId, $this->merchantId, $this->merchantPwd, $this->logger);
    }

    protected function tearDown()
    {
        $this->countryId = null;
        $this->merchantId = null;
        $this->merchantPwd = null;
        $this->paymentEventDispatcher = null;
        $this->paymentBridge = null;
        $this->paymentMethod = null;
        $this->logger = null;
    }
}
