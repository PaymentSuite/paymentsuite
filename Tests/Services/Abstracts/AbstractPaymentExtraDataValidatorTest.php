<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymentCoreBundle\Tests\Services;

use Mmoreram\PaymentCoreBundle\Tests\Sandbox\PaymentExtraDataValidator;

/**
 * Tests Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher class
 */
class AbstractPaymentExtraDataValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PaymentExtraDataValidator
     * 
     * PaymentExtraDataValidator object
     */
    private $paymentExtraDataValidator;


    /**
     * @var PaymentBridgeInterface
     * 
     * PaymentBridgeInterface instance
     */
    private $paymentBridgeInterface;


    /**
     * construct method
     */
    public function __construct()
    {

        $this->paymentBridgeInterface = $this
            ->getMockBuilder('Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentExtraDataValidator = new PaymentExtraDataValidator($this->paymentBridgeInterface);
    }


    /**
     * Testing missing extra data given a PaymentBridge object
     * 
     * @expectedException Mmoreram\PaymentCoreBundle\Exception\PaymentExtraDataFieldNotDefinedException
     */
    public function testMissingExtraData()
    {
        $this->paymentBridgeInterface
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(

                'customer_field1'   =>  'data1',
                'customer_field2'   =>  'data2',
            )));

        $this->paymentExtraDataValidator->validate();
    }


    /**
     * Testing PaymentBridge object with exactly same fields Validator needs
     */
    public function testExactlySameData()
    {
        $this->paymentBridgeInterface
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(

                'customer_field1'   =>  'data1',
                'customer_field2'   =>  'data2',
                'order_field3'      =>  'data3',
            )));

        $result = $this->paymentExtraDataValidator->validate();

        $this->assertInstanceOf('Mmoreram\PaymentCoreBundle\Tests\Sandbox\PaymentExtraDataValidator', $result);
    }


    /**
     * Testing PaymentBridge object with more fields that needed
     * 
     * This test should success, so many Payment elements should share fields, and PaymentBridge should provide them all
     */
    public function testMoreDataButValidates()
    {
        $this->paymentBridgeInterface
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array(

                'customer_field1'   =>  'data1',
                'customer_field2'   =>  'data2',
                'order_field3'      =>  'data3',
                'order_field4'      =>  'data4',
            )));

        $result = $this->paymentExtraDataValidator->validate();

        $this->assertInstanceOf('Mmoreram\PaymentCoreBundle\Tests\Sandbox\PaymentExtraDataValidator', $result);
    }
}