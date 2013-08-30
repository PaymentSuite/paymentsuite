<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\PaymentCoreBundle\Services\interfaces;


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
     * Get order
     *
     * @return Object Order object
     */
    public function getOrder();


    /**
     * Return order description
     *
     * @return string
     */
    public function getOrderDescription();


    /**
     * Return order identifier value
     *
     * @return integer
     */
    public function getOrderId();
}