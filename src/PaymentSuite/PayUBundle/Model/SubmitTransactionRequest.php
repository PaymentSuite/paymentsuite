<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Model;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuRequest;
use PaymentSuite\PayuBundle\Model\Abstracts\PayuTransaction;

/**
 * SubmitTransaction Request Model
 */
class SubmitTransactionRequest extends PayuRequest
{
    /**
     * @var PayuTransaction
     *
     * transaction
     */
    protected $transaction;

    /**
     * Sets Transaction
     *
     * @param PayuTransaction $transaction Transaction
     *
     * @return SubmitTransactionRequest Self object
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get Transaction
     *
     * @return PayuTransaction Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
