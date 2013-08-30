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


    /**
     * Get cart
     *
     * @return Object Cart object
     */
    public function getCart();


    /**
     * Return order description
     *
     * @return string
     */
    public function getCartDescription();


    /**
     * Return cart id
     *
     * @return integer
     */
    public function getCartId();
}