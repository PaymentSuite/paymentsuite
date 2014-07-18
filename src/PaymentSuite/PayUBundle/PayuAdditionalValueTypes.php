<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
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
