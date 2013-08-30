<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\CorePaymentBundle;


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