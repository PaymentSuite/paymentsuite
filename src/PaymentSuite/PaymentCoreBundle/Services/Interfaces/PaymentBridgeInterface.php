<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
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
     * Sets order to PaymentBridge
     *
     * Sets a generic Order object in the PaymentBridge.
     * This is necessary so that the bridge has a reference to
     * the order object once it gets loaded.
     * The order object doesn't need to implement any interface,
     * since it is platform-specific
     *
     * @param Object $order Order element
     */
    public function setOrder($order);

    /**
     * Gets order
     *
     * Return object stored as order
     *
     * @return Object Order object
     */
    public function getOrder();

    /**
     * Gets order given an identifier and stores it ocally
     *
     * @param integer $orderId Order identifier, usually defined as primary key or unique key
     *
     * @return Object Order object
     */
    public function findOrder($orderId);

    /**
     * Returns order identifier value
     *
     * @return integer
     */
    public function getOrderId();

    /**
     * Returns if order has already been paid
     *
     * @return boolean
     */
    public function isOrderPaid();

    /**
     * Common methods
     */

    /**
     * Gets payment amount in CENTS
     *
     * Payment amoounts always must be returned in CENTS
     * Example:
     *   USD: 10.55 -> becomes "1055"
     *
     * This means that if your platform uses floats/decimals to
     * specify amounts, you will have to convert them to
     * integer before returning them here
     *
     * @return integer
     */
    public function getAmount();

    /**
     * Gets payment currency in ISO 4217 3-character format
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
