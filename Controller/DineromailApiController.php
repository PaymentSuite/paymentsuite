<?php

namespace Scastells\DineromailApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\DineromailApiBundle\DineromailApiMethod;

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
                ->setCardNum($data['card_num'])
                ->setCardExpMonth($data['card_exp_month'])
                ->setCardExpYear($data['card_exp_year'])
                ->setCardSecurity($data['card_ccv2'])
                ->setCardQuota($data['card_quotas']);
            try{
                $this->get('dineromail-api.manager')
                    ->processPayment($paymentMethod, $data['amount']);

                $redirectUrl = $this->container->getParameter('dineromail-api.success.route');
                $redirectAppend = $this->container->getParameter('dineromail-api.success.order.append');
                $redirectAppendField = $this->container->getParameter('dineromail-api.success.order.field');

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('dineromail-api.fail.route');
                $redirectAppend = $this->container->getParameter('dineromail-api.fail.order.append');
                $redirectAppendField = $this->container->getParameter('dineromail-api.fail.order.field');
            }


        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('dineromail-api.fail.route');
            $redirectAppend = $this->container->getParameter('dineromail-api.fail.order.append');
            $redirectAppendField = $this->container->getParameter('dineromail-api.fail.order.field');
        }

        $redirectData   = $redirectAppend
            ? array(
                $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();
        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
