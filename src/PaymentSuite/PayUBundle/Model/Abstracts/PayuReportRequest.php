<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model\Abstracts;

/**
 * Abstract Model class for report request models
 */
abstract class PayuReportRequest extends PayuRequest
{
    /**
     * @var PayuDetails
     *
     * details
     */
    protected $details;

    /**
     * Sets Details
     *
     * @param PayuDetails $details Details
     *
     * @return PayuReportRequest Self object
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get Details
     *
     * @return PayuDetails Details
     */
    public function getDetails()
    {
        return $this->details;
    }
}
