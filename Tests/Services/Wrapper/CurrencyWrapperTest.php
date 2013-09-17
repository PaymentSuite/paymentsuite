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

namespace Mmoreram\PaymentCoreBundle\Tests\Services\Wrapper;

use Mmoreram\PaymentCoreBundle\Services\Wrapper\CurrencyWrapper;

/**
 * Tests Mmoreram\PaymentCoreBundle\Services\Wrapper\CurrencyWrapper class
 */
class CurrencyWrapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CartWrapper
     *
     * Cart Wrapper
     */
    private $cartWrapper;


    /**
     * Setup
     */
    public function setUp()
    {
        $this->cartWrapper = $this->getMock('Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface');
    }


    /**
     * Testing Cart wrapper currency definition
     */
    public function testPaymentBridgeCurrency()
    {
        $this->cartWrapper
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue('USD'));

        $currencyWrapper = new CurrencyWrapper($this->cartWrapper);

        $this->assertEquals('USD', $currencyWrapper->getCurrency());
    }
}