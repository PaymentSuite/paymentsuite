<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\CorePaymentBundle\Services\interfaces;


/**
 * Interface for OrderWrapper
 */
interface OrderWrapperInterface
{

     /**
     * Set order to OrderWrapper
     *
     * @var Object $order Order element
     */
    public function setOrder($order);


    /**
     * Return order identifier value
     *
     * @return integer
     */
    public function getOrderId();
}