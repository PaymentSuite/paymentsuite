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

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Interface for PaymentBridge
 */
interface PaymentBridgeRedsysInterface extends PaymentBridgeInterface
{
    /**
     * Return dsOrder identifier value
     *
     * @return integer
     */
    public function getOrderNumber();
}
