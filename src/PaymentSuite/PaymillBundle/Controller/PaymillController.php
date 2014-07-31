<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaymillBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymillBundle\PaymillMethod;

/**
 * PaymillController
 */
class PaymillController extends Controller
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
        $form = $this->get('form.factory')->create('paymill_view');
        $form->handleRequest($request);

        try {

            if (!$form->isValid()) {

                throw new PaymentException;

            }

            $data = $form->getData();
            $paymentMethod = $this->createPaymillMethod($data);
            $this
                ->get('paymill.manager')
                ->processPayment($paymentMethod, $data['amount']);

            $redirectUrl = $this->container->getParameter('paymill.success.route');
            $redirectAppend = $this->container->getParameter('paymill.success.order.append');
            $redirectAppendField = $this->container->getParameter('paymill.success.order.field');

        } catch (PaymentException $e) {

            /**
             * Must redirect to fail route
             */
            $redirectUrl = $this->container->getParameter('paymill.fail.route');
            $redirectAppend = $this->container->getParameter('paymill.fail.order.append');
            $redirectAppendField = $this->container->getParameter('paymill.fail.order.field');
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
    private function createPaymillMethod(array $data)
    {
        $paymentMethod = new PaymillMethod;
        $paymentMethod
            ->setApiToken($data['api_token'])
            ->setCreditCardNumber($data['credit_card_1'] . $data['credit_card_2'] . $data['credit_card_3'] . $data['credit_card_4'])
            ->setCreditCardOwner($data['credit_card_owner'])
            ->setCreditCardExpirationMonth($data['credit_card_expiration_month'])
            ->setCreditCardExpirationYear($data['credit_card_expiration_year'])
            ->setCreditCardSecurity($data['credit_card_security']);

        return $paymentMethod;
    }
}
