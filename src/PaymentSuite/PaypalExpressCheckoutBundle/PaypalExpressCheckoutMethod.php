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

namespace PaymentSuite\PaypalExpressCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PaypalExpressCheckoutMethod class
 */
class PaypalExpressCheckoutMethod implements PaymentMethodInterface
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
     * @var SomeExtraData
     *
     * Some extra data given by payment response
     */
    private $someExtraData;

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return PaypalExpressCheckoutMethod self Object
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     *
     * @return PaypalExpressCheckoutMethod self Object
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Set some extra data
     *
     * @param string $someExtraData Some extra data
     *
     * @return PaypalExpressCheckoutMethod self Object
     */
    public function setSomeExtraData($someExtraData)
    {
        $this->someExtraData = $someExtraData;

        return $this;
    }

    /**
     * Get some extra data
     *
     * @return array Some extra data
     */
    public function getSomeExtraData()
    {
        return $someExtraData;
    }
}
