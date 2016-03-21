<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Victor Pastor <victor.pastor@deliberry.com>
 */

namespace PaymentSuite\RedsysApiBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Interface for PaymentBridge
 */
interface PaymentBridgeRedsysApiInterface extends PaymentBridgeInterface
{
    /**
     * Returns error message
     *
     * @return string
     */
    public function getError();

    /**
     * Sets error message
     *
     * @param string $error
     */
    public function setError($error);

    /**
     * Returns error code
     *
     * @return string
     */
    public function getErrorCode();

    /**
     * Sets error code
     *
     * @param string $errorCode
     */
    public function setErrorCode($errorCode);
}
