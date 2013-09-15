<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package PaymentCoreBundle
 *
 * Denys Pasishnyi 2013
 */

namespace Mmoreram\PaymentCoreBundle\Twig;

use Twig_Extension;
use Mmoreram\PaymentCoreBundle\Services\Wrapper\CurrencyWrapper;

/**
 * Text utilities extension
 *
 */
class CurrencyExtension extends Twig_Extension
{
    /**
     * @var CurrencyWrapper
     *
     * Currency wrapper
     */
    private $currency;


    /**
     * Construct method
     * @param CurrencyWrapper $currencyWrapper
     */
    public function __construct(CurrencyWrapper $currencyWrapper)
    {
        $this->currency = $currencyWrapper;
    }

    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('setCurrency', array($this, 'setCurrency'))
        );
    }


    /**
     * Set currency code for PaymentCoreBundle
     *
     * @param string $currencyCode
     */
    public function setCurrency($currencyCode)
    {
        $this->currency->setCurrency($currencyCode);
    }


    /**
     * Return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'currency_extension';
    }
}