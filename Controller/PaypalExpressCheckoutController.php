<?php

/**
 * PaypalExpressCheckout for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalExpressCheckout
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaypalExpressCheckoutBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PaymentSuite\PaypalExpressCheckout\PaypalExpressCheckoutMethod;

/**
 * PaypalExpressCheckoutController
 */
class PaypalExpressCheckoutController extends Controller
{

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('paypal_express_checkout_view');
        $form->handleRequest($request);

        try {
            $data = $request;
            $paymentMethod = $this->createPaypalExpressCheckoutMethod($data);
            $this
                ->get('paypal_express_checkout.manager')
                ->preparePayment($paymentMethod, $ordersParameters);

            $redirectUrl = $this->container->getParameter('paypal_express_checkout.success.route');
            $redirectAppend = $this->container->getParameter('paypal_express_checkout.success.order.append');
            $redirectAppendField = $this->container->getParameter('paypal_express_checkout.success.order.field');


        } catch (PaymentException $e) {

            /**
             * Must redirect to fail route
             */
            $redirectUrl = $this->container->getParameter('paypal_express_checkout.fail.route');
            $redirectAppend = $this->container->getParameter('paypal_express_checkout.fail.order.append');
            $redirectAppendField = $this->container->getParameter('paypal_express_checkout.fail.order.field');
        }

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }


    /**
     * Given some data, creates a PaymillMethod object
     *
     * @param array $data Data
     *
     * @return PaymillMethod PaymillMethod instance
     */
    private function createPaypalExpressCheckoutMethod(array $data)
    {
        $paymentMethod = new PaypalExpressCheckoutMethod;

        return $paymentMethod;
    }
}
