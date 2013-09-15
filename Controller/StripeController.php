<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package StripeBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\StripeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use dpcat237\PaymentCoreBundle\Exception\PaymentException;
use dpcat237\StripeBundle\StripeMethod;


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

        if ($form->isValid()) {

            $data = $form->getData();

            $paymentMethod = new StripeMethod;
            $paymentMethod
                ->setAmount((float) $data['amount'])
                ->setApiToken($data['api_token'])
                ->setCreditCartNumber($data['credit_cart'])
                ->setCreditCartExpirationMonth($data['credit_cart_expiration_month'])
                ->setCreditCartExpirationYear($data['credit_cart_expiration_year'])
                ->setCreditCartSecurity($data['credit_cart_security']);

            try {
                $this
                    ->get('stripe.manager')
                    ->processPayment($paymentMethod);

                $redirectUrl = $this->container->getParameter('stripe.success.route');
                $redirectAppend = $this->container->getParameter('stripe.success.order.append');
                $redirectAppendField = $this->container->getParameter('stripe.success.order.field');
                $redirectAppendValue = $this->get('payment.order.wrapper')->getOrderId();

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('stripe.fail.route');
                $redirectAppend = $this->container->getParameter('stripe.fail.cart.append');
                $redirectAppendField = $this->container->getParameter('stripe.fail.cart.field');
                $redirectAppendValue = $this->get('payment.cart.wrapper')->getCartId();

                throw $e;
            }
        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('stripe.fail.route');
            $redirectAppend = $this->container->getParameter('stripe.fail.cart.append');
            $redirectAppendField = $this->container->getParameter('stripe.fail.cart.field');
            $redirectAppendValue = $this->get('payment.cart.wrapper')->getCartId();
        }

        $redirectData   = $redirectAppend ? array($redirectAppendField => $redirectAppendValue) : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}