<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
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
