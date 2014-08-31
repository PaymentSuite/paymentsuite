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
 * Class PayuAdditionalValueTypes
 */
class PayuAdditionalValueTypes
{
    /**
     * @var string
     *
     * TX_VALUE additional value
     */
    const TYPE_TX_VALUE = 'TX_VALUE';

    /**
     * @var string
     *
     * TX_TAX additional value
     */
    const TYPE_TX_TAX = 'TX_TAX';

    /**
     * @var string
     *
     * TX_TAX_RETURN_BASE additional value
     */
    const TYPE_TX_TAX_RETURN_BASE = 'TX_TAX_RETURN_BASE';

    /**
     * @var string
     *
     * TX_ADDITIONAL_VALUE additional value
     */
    const TYPE_TX_ADDITIONAL_VALUE = 'TX_ADDITIONAL_VALUE';
}
