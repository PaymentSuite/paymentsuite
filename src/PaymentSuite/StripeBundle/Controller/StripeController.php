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

namespace PaymentSuite\StripeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     *
     * @throws PaymentException
     */
    public function executeAction(Request $request)
    {
        /**
         * @var FormInterface $form
         */
        $form = $this
            ->get('form.factory')
            ->create('stripe_view');

        $form->handleRequest($request);

        try {
            if (!$form->isValid()) {
                throw new PaymentException();
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
        $paymentMethod = new StripeMethod();
        $paymentMethod
            ->setApiToken($data['api_token'])
            ->setCreditCardNumber($data['credit_card'])
            ->setCreditCardExpirationMonth($data['credit_card_expiration_month'])
            ->setCreditCardExpirationYear($data['credit_card_expiration_year'])
            ->setCreditCardSecurity($data['credit_card_security']);

        return $paymentMethod;
    }
}
