<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle;

/**
 * Class PayuDetailsTypes
 */
class PayuDetailsTypes
{
    /**
     * @var string
     *
     * TRANSACTION_RESPONSE_DETAIL details
     */
    const TYPE_TRANSACTION_RESPONSE_DETAIL = 'TRANSACTION_RESPONSE_DETAIL';

    /**
     * @var string
     *
     * ORDER_DETAIL details
     */
    const TYPE_ORDER_DETAIL = 'ORDER_DETAIL';

    /**
     * @var string
     *
     * ORDER_DETAIL_BY_REFERENCE_CODE details
     */
    const TYPE_ORDER_DETAIL_BY_REFERENCE_CODE = 'ORDER_DETAIL_BY_REFERENCE_CODE';
}
