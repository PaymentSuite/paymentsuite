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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaypalExpressCheckoutBundle\PaypalExpressCheckoutMethod;

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
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
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
    public function preparePayment(PaypalExpressCheckoutMethod $paypalMethod)
    {
        $orderParameters = $paymentMethod->getSomeExtraData();
        $this->paypalWrapper->request('SetExpressCheckout', $orderParameters);
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paypalMethod);

        return $this->paypalWrapper->getToken();
    }

    /**
     * Executes the payment : DoExpressCheckoutPayment
     *
     */
    public function processPayment(PaypalExpressCheckoutMethod $paymentMethod)
    {
        $orderParameters = $paymentMethod->getSomeExtraData();
        $this->paypalWrapper->request('DoExpressCheckoutPayment',$orderParameters);

        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        if ($this->getPaymentStatus($this->paypalWrapper) == 'PaymentActionCompleted') {
            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paypalMethod);
        } else {
            $this->paymentEventDispatcher->notifyPaymentOrderFail($paymentBridge, $paypalMethod);
        }

        return $this->getPaymentStatus();
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
