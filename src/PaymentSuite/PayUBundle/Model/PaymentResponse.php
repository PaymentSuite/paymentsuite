<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Model;

use JMS\Serializer\Annotation as JMS;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuResponse;

/**
 * Payment Response Model
 */
class PaymentResponse extends PayuResponse
{
    /**
     * @var string
     * @JMS\Type("string")
     *
     * error
     */
    protected $error;

    /**
     * @var string
     * @JMS\Type("PaymentSuite\PayuBundle\Model\TransactionResponse")
     *
     * transactionResponse
     */
    protected $transactionResponse;

    /**
     * Sets Error
     *
     * @param string $error Error
     *
     * @return PaymentResponse Self object
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get Error
     *
     * @return string Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets TransactionResponse
     *
     * @param \PaymentSuite\PayuBundle\Model\TransactionResponse $transactionResponse TransactionResponse
     *
     * @return PaymentResponse Self object
     */
    public function setTransactionResponse($transactionResponse)
    {
        $this->transactionResponse = $transactionResponse;

        return $this;
    }

    /**
     * Get TransactionResponse
     *
     * @return \PaymentSuite\PayuBundle\Model\TransactionResponse TransactionResponse
     */
    public function getTransactionResponse()
    {
        return $this->transactionResponse;
    }
}
