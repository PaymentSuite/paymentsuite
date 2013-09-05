<?php

/**
 * BeFactory PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <marc.morera@befactory.com>
 * @package PaymentCoreBundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymentCoreBundle;


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
}