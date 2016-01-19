<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaypalWebCheckoutBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutManager;

/**
 * Class ProcessController.
 */
class ProcessController
{
    /**
     * @var PaypalWebCheckoutManager
     *
     * PaypalWebCheckout manager
     */
    private $paypalWebCheckoutManager;

    /**
     * @var PaymentLogger
     *
     * Payment logger
     */
    private $paymentLogger;

    /**
     * Construct.
     *
     * @param PaypalWebCheckoutManager $paypalWebCheckoutManager PaypalWebCheckout manager
     * @param PaymentLogger            $paymentLogger            Payment logger
     */
    public function __construct(
        PaypalWebCheckoutManager $paypalWebCheckoutManager,
        PaymentLogger $paymentLogger
    ) {
        $this->paypalWebCheckoutManager = $paypalWebCheckoutManager;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * Process Paypal IPN notification.
     *
     * This controller handles the IPN notification.
     * The notification is sent using POST method. However,
     * we expect our internal order_id to be passed as a
     * query parameter 'order_id'. The resulting URL for
     * IPN callback notification will have the following form:
     *
     * http://my-domain.com/payment/paypal_web_checkout/process?order_id=1001
     *
     * No matter what happens here, this controller will
     * always return a 200 status HTTP response, otherwise
     * Paypal notification engine will keep on sending the
     * message.
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function processAction(Request $request)
    {
        $orderId = $request
            ->query
            ->get('order_id');

        try {
            $this
                ->paypalWebCheckoutManager
                ->processPaypalIPNMessage(
                    $orderId, $request
                    ->request
                    ->all()
                );

            $this
                ->paymentLogger
                ->log(
                    'info',
                    'Paypal payment success. Order number #' . $orderId,
                    'paypal-web-checkout'
                );
        } catch (ParameterNotReceivedException $exception) {
            $this
                ->paymentLogger
                ->log(
                    'error',
                    'Parameter ' . $exception->getMessage() . ' not received. Order number #' . $orderId,
                    'paypal-web-checkout'
                );
        } catch (PaymentException $exception) {
            $this
                ->paymentLogger
                ->log(
                    'error',
                    'Paypal payment error "' . $exception->getMessage() . '". Order number #' . $orderId,
                    'paypal-web-checkout'
                );
        }

        return new Response('OK', 200);
    }
}
