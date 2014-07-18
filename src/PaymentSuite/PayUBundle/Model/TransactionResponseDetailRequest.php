<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Model;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuReportRequest;

/**
 * TransactionResponseDetail Request Model
 */
class TransactionResponseDetailRequest extends PayuReportRequest
{
    /**
     * @var TransactionResponseDetailDetails
     *
     * details
     */
    protected $details;
}
