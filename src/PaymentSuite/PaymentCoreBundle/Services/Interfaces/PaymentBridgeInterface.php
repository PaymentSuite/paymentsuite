<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaymentCoreBundle\Services\Interfaces;

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
     * Given an order ( this object is not hinted, so every project must have its own order )
     * we store it locally.
     *
     * @param Object $order Order element
     */
    public function setOrder($order);

    /**
     * Get order
     *
     * Return object stored as order
     *
     * @return Object Order object
     */
    public function getOrder();

    /**
     * Get order given an identifier and stores locally
     *
     * @param integer $orderId Order identifier, usually defined as primary key or unique key
     *
     * @return Object Order object
     */
    public function findOrder($orderId);

    /**
     * Return order identifier value
     *
     * @return integer
     */
    public function getOrderId();

    /**
     * Return if order has already been paid
     *
     * @return boolean
     */
    public function isOrderPaid();

    /**
     * Common methods
     */

    /**
     * Get payment amount
     * Amount charged in cents
     *
     * @return float
     */
    public function getAmount();

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
