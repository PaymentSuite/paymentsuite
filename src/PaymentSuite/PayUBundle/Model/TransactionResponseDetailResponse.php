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
use PaymentSuite\PayuBundle\Model\Abstracts\PayuReportResponse;

/**
 * TransactionResponseDetail Response Model
 */
class TransactionResponseDetailResponse extends PayuReportResponse
{
    /**
     * @var TransactionResponseDetailResult
     * @JMS\Type("PaymentSuite\PayuBundle\Model\TransactionResponseDetailResult")
     *
     * result
     */
    protected $result;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * error
     */
    protected $error;

    /**
     * Sets Error
     *
     * @param string $error Error
     *
     * @return TransactionResponseDetailResponse Self object
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
}
