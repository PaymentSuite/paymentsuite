<?php

namespace PaymentSuite\GestpayBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Interface PaymentBridgeGestpayInterface.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
interface PaymentBridgeGestpayInterface extends PaymentBridgeInterface
{
    /**
     * Returns custom info to send to gestpay gateway.
     *
     * @return string
     */
    public function getCustomInfo();
}
