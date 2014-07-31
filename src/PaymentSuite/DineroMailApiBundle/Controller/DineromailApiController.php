<?php

namespace PaymentSuite\DineroMailApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\DineromailApiBundle\DineromailApiMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * DineromailApiController
 */
class DineromailApiController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('dineromail_api_view');
        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            $paymentMethod = new DineromailApiMethod();
            $paymentMethod
                ->setCardType($data['card_type'])
                ->setCardName($data['card_name'])
                ->setCardNum($data['card_num_1'].$data['card_num_2'].$data['card_num_3'].$data['card_num_4'])
                ->setCardExpMonth($data['card_exp_month'])
                ->setCardExpYear($data['card_exp_year'])
                ->setCardSecurity($data['card_ccv2'])
                ->setCardQuota($data['card_quotas']);
            try {
                $this->get('dineromail_api.manager')
                    ->processPayment($paymentMethod, $data['amount']);

                $redirectUrl = $this->container->getParameter('dineromail_api.success.route');
                $redirectAppend = $this->container->getParameter('dineromail_api.success.order.append');
                $redirectAppendField = $this->container->getParameter('dineromail_api.success.order.field');

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('dineromail_api.fail.route');
                $redirectAppend = $this->container->getParameter('dineromail_api.fail.order.append');
                $redirectAppendField = $this->container->getParameter('dineromail_api.fail.order.field');
            }

        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('dineromail_api.fail.route');
            $redirectAppend = $this->container->getParameter('dineromail_api.fail.order.append');
            $redirectAppendField = $this->container->getParameter('dineromail_api.fail.order.field');
        }

        $redirectData   = $redirectAppend
            ? array(
                $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
