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
