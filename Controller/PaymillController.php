<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\PaymillBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Befactory\CorePaymentBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Befactory\PaymillBundle\PaymillMethod;


/**
 * @Route("/payment/paymill")
 */
class PaymillController extends Controller
{

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @Route("/execute", name="paymill_execute")
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('paymill_view');
        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            $paymentMethod = new PaymillMethod;
            $paymentMethod
                ->setAmount((int) $data['amount'])
                ->setCreditCartNumber($data['credit_cart_1'] . $data['credit_cart_2'] . $data['credit_cart_3'] . $data['credit_cart_4'])
                ->setCreditCartOwner($data['credit_cart_owner'])
                ->setCreditCartExpirationMonth($data['credit_cart_expiration_month'])
                ->setCreditCartExpirationYear($data['credit_cart_expiration_year'])
                ->setCreditCartSecurity($data['credit_cart_security']);

            try {
                $this
                    ->get('paymill.manager')
                    ->processPayment($paymentMethod);

                $redirectUrl = $this->container->getParameter('paymill.success.route');
                $redirectAppendOrder = $this->container->getParameter('paymill.success.order.append');
                $redirectAppendOrderField = $this->container->getParameter('paymill.success.order.field');

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('paymill.fail.route');

                $redirectUrl = $this->container->getParameter('paymill.fail.route');
                $redirectAppendOrder = $this->container->getParameter('paymill.fail.order.append');
                $redirectAppendOrderField = $this->container->getParameter('paymill.fail.order.field');
            }
        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('paymill.routes.fail');

            $redirectUrl = $this->container->getParameter('paymill.fail.route');
            $redirectAppendOrder = $this->container->getParameter('paymill.fail.order.append');
            $redirectAppendOrderField = $this->container->getParameter('paymill.fail.order.field');
        }

        $redirectData = array();
        if ($redirectAppendOrder) {

            $orderWrapper = $this->get('payment.order.wrapper');
            $redirectData = array(
                $redirectAppendOrderField => $orderWrapper->getOrderId(),
            );
        }

        return new RedirectResponse($this->generateUrl($redirectUrl, $redirectData));
    }
}