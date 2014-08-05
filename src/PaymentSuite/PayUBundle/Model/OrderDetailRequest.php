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
 * OrderDetail Request Model
 */
class OrderDetailRequest extends PayuReportRequest
{
    /**
     * @var OrderDetailDetails
     *
     * details
     */
    protected $details;
}
