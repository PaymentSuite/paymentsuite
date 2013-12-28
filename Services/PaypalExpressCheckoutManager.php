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

namespace PaymentSuite\PaypalExpressCheckout\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaypalExpressCheckout\PaypalExpressCheckoutMethod;
use PayPal\CoreComponentTypes\BasicAmountType;


/**
 * Paypal Express Checkout manager
 */
class PaypalExpressCheckoutManager
{
    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;


    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;

    /**
     * @var Array config
     *
     * Paypal Express Checkout configuration
     */
    protected $config;


    /**
     * Construct method for paypal manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
    }



}