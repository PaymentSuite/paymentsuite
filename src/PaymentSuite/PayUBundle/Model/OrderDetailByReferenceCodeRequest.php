<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model;

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
