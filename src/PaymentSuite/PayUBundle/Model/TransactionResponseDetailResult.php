<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model;

use JMS\Serializer\Annotation as JMS;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuResult;

/**
 * TransactionResponseDetail Result Model
 */
class TransactionResponseDetailResult extends PayuResult
{
    /**
     * @var TransactionResponseDetailPayload
     * @JMS\Type("PaymentSuite\PayuBundle\Model\TransactionResponseDetailPayload")
     *
     * payload
     */
    protected $payload;
}
