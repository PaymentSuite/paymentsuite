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

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Interface for PaymentBridge.
 */
interface PaymentBridgeRedsysInterface extends PaymentBridgeInterface
{
    /**
     * Return dsOrder identifier value.
     *
     * @return int
     */
    public function getOrderNumber();
}
