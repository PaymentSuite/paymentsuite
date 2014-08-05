<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuDetails;

/**
 * TransactionResponseDetail Details Model
 */
class TransactionResponseDetailDetails extends PayuDetails
{
    /**
     * @var string
     *
     * transactionId
     */
    protected $transactionId;

    /**
     * Sets TransactionId
     *
     * @param string $transactionId TransactionId
     *
     * @return TransactionResponseDetailDetails Self object
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get TransactionId
     *
     * @return string TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
