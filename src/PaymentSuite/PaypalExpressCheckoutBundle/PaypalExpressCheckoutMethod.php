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

namespace PaymentSuite\PaypalExpressCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PaypalExpressCheckoutMethod class
 */
final class PaypalExpressCheckoutMethod implements PaymentMethodInterface
{
    /**
     * Get PaypalExpressCheckout method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'paypal_express_checkout';
    }

    /**
     * @var float
     *
     * PaypalExpressCheckout amount
     */
    private $amount;

    /**
     * @var string
     *
     * PaypalExpressCheckout orderNumber
     */
    private $orderNumber;

    /**
     * @var array
     *
     * Some extra data given by payment response
     */
    private $someExtraData;

    /**
     * Construct
     *
     * @param float  $amount        Amount
     * @param string $orderNumber   Order Number
     * @param array  $someExtraData Some extra data
     */
    private function __construct(
        $amount,
        $orderNumber,
        array $someExtraData
    ) {
        $this->amount = $amount;
        $this->orderNumber = $orderNumber;
        $this->someExtraData = $someExtraData;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Get some extra data
     *
     * @return array Some extra data
     */
    public function getSomeExtraData()
    {
        return $this->someExtraData;
    }
}
