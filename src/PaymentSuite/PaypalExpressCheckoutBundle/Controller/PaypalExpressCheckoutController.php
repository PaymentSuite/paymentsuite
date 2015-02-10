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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
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
            $data = $form->getData();
            $paymentMethod = $this->createPaypalExpressCheckoutMethod($data);
            $this
                ->get('paypal_express_checkout.manager')
                ->preparePayment($paymentMethod);

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
        $paymentMethod = new PaypalExpressCheckoutMethod();
        $paymentMethod->setAmount($data['amount'])
            ->setCurrency($data['currency'])
            ->setSomeExtraData($data['paypal_express_params'])
        ;

        return $paymentMethod;
    }
}
