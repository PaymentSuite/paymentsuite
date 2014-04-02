<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Model;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuReportRequest;

/**
 * OrderDetailByReferenceCode Request Model
 */
class OrderDetailByReferenceCodeRequest extends PayuReportRequest
{
    /**
     * @var OrderDetailByReferenceCodeDetails
     *
     * details
     */
    protected $details;
}
