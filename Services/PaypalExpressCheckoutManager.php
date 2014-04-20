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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaypalExpressCheckoutBundle\PaypalExpressCheckoutMethod;
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
     * @var PaypalExpressCheckoutTransactionWrapper $paypalWrapper
     *
     * Paypal Express Checkout wrapper
     */
    protected $paypalWrapper;

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
     * @param PaypalExpressCheckoutTransactionWrapper Paypal Wrapper
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, PaypalExpressCheckoutTransactionWrapper $paypalWrapper)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->paypalWrapper = $paypalWrapper;
    }

    /**
     * See also PaypalExpressCheckout Api Integration : https://devtools-paypal.com/guide/expresscheckout/php?success=true&token=EC-39A62694YH391933H&PayerID=22GDTKRPSZFWS
     * Initiate the payment : SetExpressCheckout
     *
     */
    public function preparePayment(PaypalExpressCheckoutMethod $paypalMethod, $orderParameters)
    {
        $this->paypalWrapper->request('SetExpressCheckout', $orderParameters);
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paypalMethod);

        return $this->paypalWrapper->getToken();
    }

    /**
     * Executes the payment : DoExpressCheckoutPayment
     *
     */
    public function processPayment(PaypalExpressCheckoutMethod $paymentMethod, $orderParameters)
    {
        $this->paypalWrapper->request('DoExpressCheckoutPayment',$orderParameters);

        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        if ($this->getPaymentStatus($this->paypalWrapper) == 'PaymentActionCompleted'){
            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paypalMethod);
        }else {
            $this->paymentEventDispatcher->notifyPaymentOrderFail($paymentBridge, $paypalMethod);
        }
    }

    /**
     * Get the payment status : GetExpressCheckoutDetails
     *
     */
    public function getPaymentStatus(PaypalExpressCheckoutTransactionWrapper $paypalWrapper)
    {
        $paypalWrapper->request('GetExpressCheckoutDetails', $paypalWrapper->getToken());
        $response = $paypalWrapper->getResponse();
        return $response['CHECKOUTSTATUS'];
    }
}
