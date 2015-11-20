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

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class DummyPaymentBridge
 *
 * @author Mickaël Andrieu <andrieu.travail@gmail.com>
 */
class DummyPaymentBridge implements PaymentBridgeInterface
{

    /**
     * @var Order
     *
     * Order object
     */
    private $order;

    /**
     * Set order to OrderWrapper
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
    public function getOrderDescription()
    {
        return '';
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
     * Given an id, find Order
     *
     * @return Object order
     */
    public function findOrder($orderId)
    {
        return $this->order;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return 'EUR';
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return 1000;
    }

    /**
     * Get extra data
     *
     * @return array
     */
    public function getExtraData()
    {
        return [
            'items' => [
                [
                    'item_name' => 'Item name 1',
                    'amount' => 100,
                    'quantity' => 1,
                    'currency_code' => 'EUR',
                ],
            ],
        ];
    }

    /**
     * Get payment status
     *
     * @return bool
     */
    public function isOrderPaid()
    {
        return true;
    }
}
