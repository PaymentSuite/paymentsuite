<?php

namespace PaymentSuite\RedsysBundle\Tests\Fixtures;

use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;

class DummyPaymentBridge implements PaymentBridgeRedsysInterface
{
    private $order;

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function findOrder($orderId)
    {
        if(1 === $orderId){
            return new \stdClass();
        }

        return null;
    }

    public function getOrderId()
    {
        return 1;
    }

    public function isOrderPaid()
    {
    }

    public function getAmount()
    {
        return 9999;
    }

    public function getCurrency()
    {
        return 'EUR';
    }

    public function getExtraData()
    {
        return [];
    }

    public function getOrderNumber()
    {
        return '1T54321';
    }
}
