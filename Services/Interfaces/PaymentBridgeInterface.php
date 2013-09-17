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

namespace Mmoreram\PaymentCoreBundle\Services\interfaces;

/**
 * Interface for PaymentBridge
 */
interface PaymentBridgeInterface
{

    /**
     * Order zone
     * 
     * Brings all needed order information
     */

    /**
     * Set order to PaymentBridge
     *
     * @param Object $order Order element
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


    /**
     * Common methods
     */


    /**
     * Get payment currency
     * 
     * @return string
     */
    public function getCurrency();


    /**
     * Get extra data
     * 
     * Each payment platform should define what extra data is needed to be implemented
     * 
     * @return array Hash object with needed data
     */
    public function getExtraData();
}