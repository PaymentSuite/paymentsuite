<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle;

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

    /**
     * @var string
     *
     * TRANSACTION_RESPONSE_DETAIL request
     */
    const TYPE_TRANSACTION_RESPONSE_DETAIL = 'TRANSACTION_RESPONSE_DETAIL';

    /**
     * @var string
     *
     * ORDER_DETAIL request
     */
    const TYPE_ORDER_DETAIL = 'ORDER_DETAIL';

    /**
     * @var string
     *
     * ORDER_DETAIL_BY_REFERENCE_CODE request
     */
    const TYPE_ORDER_DETAIL_BY_REFERENCE_CODE = 'ORDER_DETAIL_BY_REFERENCE_CODE';
}
