<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle;

/**
 * Class PayuRequestTypes
 */
class PayuRequestTypes
{
    /**
     * @var string
     *
     * PING request
     */
    const TYPE_PING = 'PING';

    /**
     * @var string
     *
     * GET_PAYMENT_METHODS request
     */
    const TYPE_GET_PAYMENT_METHODS = 'GET_PAYMENT_METHODS';

    /**
     * @var string
     *
     * SUBMIT_TRANSACTION request
     */
    const TYPE_SUBMIT_TRANSACTION = 'SUBMIT_TRANSACTION';
}
