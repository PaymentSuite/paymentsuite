<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\StripeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\StripeBundle\StripeMethod;

/**
 * StripeController
 */
class StripeController extends Controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     * @throws PaymentException
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('stripe_view');
        $form->handleRequest($request);

        try {
            if (!$form->isValid()) {
                throw new PaymentException;
            }

            $data = $form->getData();
            $paymentMethod = $this->createStripeMethod($data);
            $this
                ->get('stripe.manager')
                ->processPayment($paymentMethod, $data['amount']);

            $redirectUrl = $this->container->getParameter('stripe.success.route');
            $redirectAppend = $this->container->getParameter('stripe.success.order.append');
            $redirectAppendField = $this->container->getParameter('stripe.success.order.field');
        } catch (PaymentException $e) {
            /**
             * Must redirect to fail route
             */
            $redirectUrl = $this->container->getParameter('stripe.fail.route');
            $redirectAppend = $this->container->getParameter('stripe.fail.order.append');
            $redirectAppendField = $this->container->getParameter('stripe.fail.order.field');
        }

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId()
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }

    /**
     * Given some data, creates a StripeMethod object
     *
     * @param array $data Data
     *
     * @return StripeMethod StripeMethod instance
     */
    private function createStripeMethod(array $data)
    {
        $paymentMethod = new StripeMethod;
        $paymentMethod
            ->setApiToken($data['api_token'])
            ->setCreditCartNumber($data['credit_cart'])
            ->setCreditCartExpirationMonth($data['credit_cart_expiration_month'])
            ->setCreditCartExpirationYear($data['credit_cart_expiration_year'])
            ->setCreditCartSecurity($data['credit_cart_security']);

        return $paymentMethod;
    }
}
