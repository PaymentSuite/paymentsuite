<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalBundle
 *
 * Marc Morera 2013
 */

namespace Mandrieu\PaymillBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mandrieu\PaypalBundle\PaypalMethod;


/**
 * PaypalController
 */
class PaypalController extends Controller
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
        $form = $this->get('form.factory')->create('paypal_view');
        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            $paymentMethod = new PaypalMethod;
            $paymentMethod
                ->setApiToken($data['api_token'])
                ->setCreditCardNumber($data['credit_card_1'] . $data['credit_card_2'] . $data['credit_card_3'] . $data['credit_card_4'])
                ->setCreditCardOwner($data['credit_card_owner'])
                ->setCreditCardExpirationMonth($data['credit_card_expiration_month'])
                ->setCreditCardExpirationYear($data['credit_card_expiration_year'])
                ->setCreditCardSecurity($data['credit_card_security']);

            try {
                $this
                    ->get('paypal.manager')
                    ->processPayment($paymentMethod, $data['amount']);

                $redirectUrl = $this->container->getParameter('paypal.success.route');
                $redirectAppend = $this->container->getParameter('paypal.success.order.append');
                $redirectAppendField = $this->container->getParameter('paypal.success.order.field');

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('paypal.fail.route');
                $redirectAppend = $this->container->getParameter('paypal.fail.order.append');
                $redirectAppendField = $this->container->getParameter('paypal.fail.order.field');

                throw $e;
            }
        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('paypal.fail.route');
            $redirectAppend = $this->container->getParameter('paypal.fail.order.append');
            $redirectAppendField = $this->container->getParameter('paypal.fail.order.field');
        }

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}