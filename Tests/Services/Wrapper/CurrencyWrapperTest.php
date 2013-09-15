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
     * @var string
     * 
     * Default currency value
     */
    const DEFAULT_CURRENCY = 'EUR';


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
        $this->cartWrapper = $this->getMock('Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface');
    }


    /**
     * Testing Cart wrapper currency definition
     */
    public function testCartWrapperCurrency()
    {
        $this->cartWrapper
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(null));

        $currencyWrapper = new CurrencyWrapper(self::DEFAULT_CURRENCY, $this->cartWrapper);

        $this->assertEquals(self::DEFAULT_CURRENCY, $currencyWrapper->getCurrency());
    }


    /**
     * Testing Cart wrapper currency definition
     */
    public function testDefaultCurrency()
    {
        $this->cartWrapper
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue('USD'));

        $currencyWrapper = new CurrencyWrapper(self::DEFAULT_CURRENCY, $this->cartWrapper);

        $this->assertEquals('USD', $currencyWrapper->getCurrency());
    }
}