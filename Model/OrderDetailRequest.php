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