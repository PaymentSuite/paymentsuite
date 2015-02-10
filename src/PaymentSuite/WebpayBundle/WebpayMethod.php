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

namespace PaymentSuite\WebpayBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\WebpayBundle\Model\Transaction;

/**
 * WebpayMethod
 */
class WebpayMethod implements PaymentMethodInterface
{
    /**
     * Webpay method name
     */
    const WEBPAY_METHOD_NAME = 'Webpay';

    /**
     * @var \PaymentSuite\WebpayBundle\Model\Transaction
     *
     * Transaction
     */
    protected $transaction;

    /**
     * @var string
     *
     * Session Id
     */
    protected $sessionId;

    /**
     * Get Webpay method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return $this::WEBPAY_METHOD_NAME;
    }

    /**
     * Sets Transaction
     *
     * @param \PaymentSuite\WebpayBundle\Model\Transaction $transaction Transaction
     *
     * @return WebpayMethod Self object
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get Transaction
     *
     * @return \PaymentSuite\WebpayBundle\Model\Transaction Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Sets SessionId
     *
     * @param string $sessionId SessionId
     *
     * @return WebpayMethod Self object
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get SessionId
     *
     * @return string SessionId
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }
}
