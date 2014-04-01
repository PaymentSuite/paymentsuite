<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\PayuBundle\Model\TransactionResponse;

/**
 * PayuMethod
 */
class PayuMethod implements PaymentMethodInterface
{
    /**
     * Method name
     */
    const METHOD_NAME = 'Payu';

    /**
     * @var TransactionResponse
     *
     * Transaction
     */
    protected $transaction;

    /**
     * Get Payu method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return $this::METHOD_NAME;
    }

    /**
     * Sets Transaction
     *
     * @param \PaymentSuite\PayuBundle\Model\TransactionResponse $transaction Transaction
     *
     * @return PayuMethod Self object
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get Transaction
     *
     * @return \PaymentSuite\PayuBundle\Model\TransactionResponse Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}