<?php

namespace PaymentSuite\GestpayBundle\Tests\Fixtures;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

class DummyPaymentBridge implements PaymentBridgeInterface
{
    private $currency;

    public function __construct()
    {
        $this->currency = 'EUR';
    }

    public function setOrder($order)
    {
    }

    public function getOrder()
    {
    }

    public function findOrder($orderId)
    {
    }

    public function getOrderId()
    {
        return 123;
    }

    public function isOrderPaid()
    {
    }

    public function getAmount()
    {
        return 10051;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getExtraData()
    {
        return [];
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }
}
