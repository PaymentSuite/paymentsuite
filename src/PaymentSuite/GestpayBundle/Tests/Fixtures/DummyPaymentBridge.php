<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\GestpayBundle\Tests\Fixtures;

use PaymentSuite\GestpayBundle\Services\Interfaces\PaymentBridgeGestpayInterface;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class DummyPaymentBridge.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class DummyPaymentBridge implements PaymentBridgeGestpayInterface
{
    private $currency;

    private $customInfo;

    public function __construct($customInfo = [])
    {
        $this->currency = 'EUR';
        $this->customInfo = $customInfo;
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

    public function getCustomInfo()
    {
        return $this->customInfo;
    }
}
