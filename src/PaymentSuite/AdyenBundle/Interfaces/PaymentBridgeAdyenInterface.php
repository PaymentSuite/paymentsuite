<?php
/*
 * This file is part of the Mascoteros package.
 *
 * Copyright (c) 2015-2016 Mascoteros.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */
namespace PaymentSuite\AdyenBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Interface for PaymentBridge
 */
interface PaymentBridgeAdyenInterface extends PaymentBridgeInterface
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
