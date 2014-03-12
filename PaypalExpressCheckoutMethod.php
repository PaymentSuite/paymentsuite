<?php

/**
 * PaypalExpressCheckout for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalExpressCheckout
 *
 * Mickael Andrieu 2013
 */

namespace PaymentSuite\PaypalExpressCheckout;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PayPal\Api\Payer;

/**
 * PaypalMethod class
 */
class PaypalExpressCheckoutMethod implements PaymentMethodInterface
{

    /**
     * Get Paypal method name
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