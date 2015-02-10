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
