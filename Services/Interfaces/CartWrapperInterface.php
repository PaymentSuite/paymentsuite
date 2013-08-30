<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\CorePaymentBundle\Services\interfaces;

/**
 * Interface for CartWrapper
 */
interface CartWrapperInterface
{

    /**
     * Return current cart amount.
     *
     * This is an interface.
     * Each project must implement this interface with current customer cart
     *
     * @return float Cart amount
     */
    public function getAmount();
}