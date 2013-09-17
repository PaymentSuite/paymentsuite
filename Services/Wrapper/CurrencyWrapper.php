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

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

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
     * @param PaymentBridgeInterface $paymentBridgeInterface cart wrapper, necessary to take dynamic currency
     */
    public function __construct(PaymentBridgeInterface $paymentBridgeInterface)
    {
        $this->currency = $paymentBridgeInterface->getCurrency();
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
}