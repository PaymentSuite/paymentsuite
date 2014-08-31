<?php

/**
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
 * Class PayuTransactionTypes
 */
class PayuTransactionTypes
{
    /**
     * @var string
     *
     * AUTHORIZATION transaction
     */
    const TYPE_AUTHORIZATION = 'AUTHORIZATION';

    /**
     * @var string
     *
     * AUTHORIZATION_AND_CAPTURE transaction
     */
    const TYPE_AUTHORIZATION_AND_CAPTURE = 'AUTHORIZATION_AND_CAPTURE';

    /**
     * @var string
     *
     * CAPTURE transaction
     */
    const TYPE_CAPTURE = 'CAPTURE';

    /**
     * @var string
     *
     * VOID transaction
     */
    const TYPE_VOID = 'VOID';

    /**
     * @var string
     *
     * REFUND transaction
     */
    const TYPE_REFUND = 'REFUND';
}
