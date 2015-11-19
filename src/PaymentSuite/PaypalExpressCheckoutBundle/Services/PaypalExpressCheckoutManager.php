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
use PaymentSuite\PaymentCoreBundle\ValueObject\PaypalExpressCheckoutResponse;
use PaymentSuite\PaypalExpressCheckoutBundle\PaypalExpressCheckoutMethod;
use PaymentSuite\PaypalExpressCheckoutBundle\Services\Wrapper\PaypalExpressCheckoutRequester;

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
    private $paymentEventDispatcher;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    private $paymentBridge;

    /**
     * @var PaypalExpressCheckoutRequester
     *
     * Paypal Express Checkout requester
     */
    private $paypalExpressCheckoutRequester;

    /**
     * Construct method for paypal manager
     *
     * @param PaymentEventDispatcher         $paymentEventDispatcher         Event dispatcher
     * @param PaymentBridgeInterface         $paymentBridge                  Payment Bridge
     * @param PaypalExpressCheckoutRequester $paypalExpressCheckoutRequester Paypal Express Checkout requester
     */
    public function __construct(
        PaymentEventDispatcher $paymentEventDispatcher,
        PaymentBridgeInterface $paymentBridge,
        PaypalExpressCheckoutRequester $paypalExpressCheckoutRequester
    ) {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->paypalExpressCheckoutRequester = $paypalExpressCheckoutRequester;
    }

    /**
     * See also PaypalExpressCheckout Api Integration : https://devtools-paypal.com/guide/expresscheckout/php?success=true&token=EC-39A62694YH391933H&PayerID=22GDTKRPSZFWS
     * Initiate the payment : SetExpressCheckout
     *
     * @param PaypalExpressCheckoutMethod $paypalMethod Paypal method
     *
     * @return string response token
     */
    public function preparePayment(PaypalExpressCheckoutMethod $paypalMethod)
    {
        $response = $this
            ->paypalExpressCheckoutRequester
            ->request(
                'SetExpressCheckout',
                $paypalMethod->getSomeExtraData()
            );

        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paypalMethod
            );

        return $response->getToken();
    }

    /**
     * Executes the payment : DoExpressCheckoutPayment
     *
     * @param PaypalExpressCheckoutMethod $paypalMethod Paypal method
     *
     * @return string Payment status
     */
    public function processPayment(PaypalExpressCheckoutMethod $paypalMethod)
    {
        $response = $this
            ->paypalExpressCheckoutRequester
            ->request(
                'DoExpressCheckoutPayment',
                $paypalMethod->getSomeExtraData()
            );

        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $paypalMethod
            );

        $paymentStatus = $this->getPaymentStatus($response);
        if ('PaymentActionCompleted' == $paymentStatus) {
            $this->paymentEventDispatcher->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $paypalMethod
            );
        } else {
            $this->paymentEventDispatcher->notifyPaymentOrderFail(
                $this->paymentBridge,
                $paypalMethod
            );
        }

        return $paymentStatus;
    }

    /**
     * Get the payment status : GetExpressCheckoutDetails
     *
     * @param PaypalExpressCheckoutResponse $response Response
     *
     * @return string Checkout status
     */
    public function getPaymentStatus(PaypalExpressCheckoutResponse $response)
    {
        $this
            ->paypalExpressCheckoutRequester
            ->request(
                'GetExpressCheckoutDetails',
                $response->getToken()
            )
            ->getCheckoutStatus();
    }
}
