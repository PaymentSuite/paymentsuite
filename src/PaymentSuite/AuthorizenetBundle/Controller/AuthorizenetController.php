<?php

/**
 * AuthorizenetBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package AuthorizenetBundle
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\AuthorizenetBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\AuthorizenetBundle\AuthorizenetMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * AuthorizenetController
 */
class AuthorizenetController extends Controller
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
        $form = $this->get('form.factory')->create('authorizenet_view');
        $form->handleRequest($request);
        $responseData = $this->processPaymentData($form);

        return $this->redirect($this->generateUrl($responseData['redirectUrl'], $responseData['redirectData']));
    }

    private function processPaymentData(Form $form)
    {
        if ($form->isValid()) {
            $data = $form->getData();
            $paymentMethod = new AuthorizenetMethod;
            $paymentMethod
                ->setCreditCartNumber($data['credit_cart'])
                ->setCreditCartExpirationMonth($data['credit_cart_expiration_month'])
                ->setCreditCartExpirationYear($data['credit_cart_expiration_year']);
            try {
                $this->get('authorizenet.manager')->processPayment($paymentMethod);

                $redirectUrl = $this->container->getParameter('authorizenet.success.route');
                $redirectAppend = $this->container->getParameter('authorizenet.success.order.append');
                $redirectAppendField = $this->container->getParameter('authorizenet.success.order.field');
            } catch (PaymentException $e) {
                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('authorizenet.fail.route');
                $redirectAppend = $this->container->getParameter('authorizenet.fail.order.append');
                $redirectAppendField = $this->container->getParameter('authorizenet.fail.order.field');

                throw $e;
            }
        } else {
            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('authorizenet.fail.route');
            $redirectAppend = $this->container->getParameter('authorizenet.fail.order.append');
            $redirectAppendField = $this->container->getParameter('authorizenet.fail.order.field');
        }
        $redirectData   = $redirectAppend ? array($redirectAppendField => $this->get('payment.bridge')->getOrderId()) : array();
        $returnData['redirectUrl'] = $redirectUrl;
        $returnData['redirectData'] = $redirectData;

        return $returnData;
    }
}
