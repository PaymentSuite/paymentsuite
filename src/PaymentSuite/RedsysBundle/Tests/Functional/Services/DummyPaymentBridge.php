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

namespace PaymentSuite\RedsysBundle\Tests\Functional\Services;

use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;

class DummyPaymentBridge implements PaymentBridgeRedsysInterface
{
    /**
     * @var Object
     *
     * Order object
     */
    private $order;

    /**
     * @var integer
     *
     */
    private $amount;

    /**
     * @var string
     *
     */
    private $orderNumber;

    public function getPaymentName()
    {
        return 'redsys';
    }

    /**
     * Set order to PaymentBridge
     *
     * @var Object $order Order element
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Return order
     *
     * @return Object order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Return order identifier value
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->order->getId();
    }

    /**
     * Get order given an identifier and stores locally
     *
     * @param integer $orderId Order identifier, usually defined as primary key or unique key
     *
     * @return Object Order object
     */
    public function findOrder($orderId)
    {
        return $this->order;
    }

    /**
     * Get the currency in which the order is paid
     *
     * @return string
     */
    public function getCurrency()
    {
        /*
        * Set your static or dynamic currency
        */

        return 'EUR'; //EUR
    }

    /**
     * Get total order amount in cents
     *
     * @return integer
     */
    public function getAmount()
    {
        /*
        * Return payment amount (in cents)
        */

        return 1000;
    }

    /**
     * Return if order has already been paid
     *
     * @return boolean
     */
    public function isOrderPaid()
    {
        return true;
    }

    /**
     * Get extra data
     *
     * @return array
     */
    public function getExtraData()
    {
        return ['terminal' => '001'];
    }

    /**
     * Return dsOrder identifier value
     *
     * @return integer
     */
    public function getOrderNumber()
    {
        return mt_rand(0, 99999);
    }
}
