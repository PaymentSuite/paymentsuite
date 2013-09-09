<?php

/**
 * BeFactory PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Mmoreram 2013
 */

namespace Mmoreram\PaymentCoreBundle\Services\interfaces;

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
     * Get order given an identifier
     * 
     * @param integer $orderId Order identifier, usually defined as primary key or unique key
     *
     * @return Object Order object
     */
    public function findOrder($orderId);


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