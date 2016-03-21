<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2015 Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author
 */

namespace PaymentSuite\RedsysApiBundle\Entity;

class Transaction
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     *
     * Order id as seen from the payment processor.
     * This needs to be a different field than the
     * orderId since this particular provider (RedSys)
     * does not allow the same <DS_ORDERID> value for
     * different transaction, event for failed one.
     * In short, RedSys sees <DS_ORDERID> as a
     * UNIQUE TRANSACTION ID
     */
    protected $redsysUniqueTransactionId;

    /**
     * @var string
     *
     * Order if from the payment client
     */
    protected $orderId;

    /**
     * @var integer
     *
     * Transaction amount in cents
     */
    protected $amount;

    /**
     * @var string
     *
     * A – Ordinary payment
     * 1 – Preauthorization
     * 2 – Confirmation
     * 3 – Automatic refund
     * 5 – Recurring payments
     * 6 – Successive transaction
     * 9 – Preauthorization cancel
     * O – Deferred authorization
     * P - Deferred authorization confirm
     * Q - Deferred authorization cancel
     * R – Initial authorization recurring deferred
     * S – Authorization successive recurring deferred
     */
    protected $transactionType = 'A';

    /**
     * @var string
     */
    protected $returnCode;

    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $authorizationCode;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @param $orderId
     * @param $amount
     * @param $transactionType
     * @param $returnCode
     * @param $errorCode
     * @param $authorizationCode
     * @param $message
     */
    function __construct(
        $orderId,
        $redsysUniqueTransactionId,
        $amount,
        $transactionType,
        $returnCode,
        $errorCode,
        $authorizationCode,
        $message)
    {
        $this->orderId = $orderId;
        $this->redsysUniqueTransactionId = $redsysUniqueTransactionId;
        $this->amount = $amount;
        $this->transactionType = $transactionType;
        $this->returnCode = $returnCode;
        $this->errorCode = $errorCode;
        $this->authorizationCode = $authorizationCode;
        $this->message = $message;

        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getRedsysUniqueTransactionId()
    {
        return $this->redsysUniqueTransactionId;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}