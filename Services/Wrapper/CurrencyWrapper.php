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

namespace Mmoreram\PaymentCoreBundle\Services\Wrapper;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;

/**
 * Cart to order service
 */
class CurrencyWrapper
{
    /**
     * @var string
     *
     * Currency code
     */
    private $currency;

    /**
     * Construct method
     *
     * @param string               $currency    currency param
     * @param CartWrapperInterface $cartWrapper cart wrapper, necessary to take dynamic currency
     */
    public function __construct($currency, CartWrapperInterface $cartWrapper)
    {
        //if defined dynamic currency take it from wrapper, if not from
        if (method_exists($cartWrapper, "getCurrency")) {
            $this->setCurrency($cartWrapper->getCurrency());
        } else {
            $this->setCurrency($currency);
        }
    }

    /**
     * Return set currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    /**
     * Set currency
     *
     * @param string $currency currency code
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
}