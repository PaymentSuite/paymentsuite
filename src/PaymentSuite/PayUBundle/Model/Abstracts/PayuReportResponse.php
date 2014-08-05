<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model\Abstracts;

/**
 * Abstract Model class for report response models
 */
abstract class PayuReportResponse extends PayuResponse
{
    /**
     * @var PayuResult
     *
     * result
     */
    protected $result;

    /**
     * Sets Result
     *
     * @param PayuResult $result Result
     *
     * @return PayuReportResponse Self object
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get Result
     *
     * @return PayuResult Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
