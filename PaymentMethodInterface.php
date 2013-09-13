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

namespace Mmoreram\PaymentCoreBundle;


/**
 * Interface for all type of payments
 */
interface PaymentMethodInterface
{

    /**
     * Return type of payment name
     *
     * @return string
     */
    public function getPaymentName();


    /**
     * Return type of payment name
     *
     * @return string
     */
    public function getAmount();


    /**
     * Return  payment amount
     * 
     * @return string
     */
    public function getCurrency();
}