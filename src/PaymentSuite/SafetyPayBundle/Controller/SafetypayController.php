<?php

namespace PaymentSuite\SafetyPayBundle\Controller;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PaymentSuite\SafetypayBundle\SafetypayMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * Class SafetypayController
 */
class SafetypayController extends controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @throws PaymentOrderNotFoundException
     * @return RedirectResponse
     *
     * @Method("POST")
     * @Template()
     */
    public function executeAction(Request $request)
    {
        $paymentMethod = new SafetypayMethod();
        $paymentBridge = $this->get('payment.bridge');

        /**
         * New order from cart must be created right here
         */
        $this->get('payment.event.dispatcher')->notifyPaymentOrderLoad($paymentBridge, $paymentMethod);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        $safetyPayTransaction = $paymentBridge->getOrderId() . '#' . date('Ymdhis');
        $paymentMethod->setReference($paymentBridge->getOrderId() . '#' . date('Ymdhis'));
//        $safetyPayTransaction = $paymentBridge->getOrderId() . date('Ymdhis');
//        $paymentMethod->setReference($paymentBridge->getOrderId() . date('Ymdhis'));

        /**
         * Loading success route for returning from safetypay
         * Success return, page where you want to redirect the shopper after the payment
         * of a transaction having a successful response from the Online Banking System
         */
        $redirectSuccessUrl = $this->container->getParameter('safetypay.success.route');
        $redirectSuccessAppend = $this->container->getParameter('safetypay.success.order.append');
        $redirectSuccessAppendField = $this->container->getParameter('safetypay.success.order.field');

        $redirectSuccessData    = $redirectSuccessAppend
            ? array(
                $redirectSuccessAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();

        $successRoute = $this->generateUrl($redirectSuccessUrl, $redirectSuccessData, true);

        /**
         * Loading fail route for returning from safetypay
         * Error return, page where you want redirect the shopper after the payment
         * of a transaction and having an error response from the Electronic Banking
         * System
         *
         */
        $redirectFailUrl = $this->container->getParameter('safetypay.fail.route');
        $redirectFailAppend = $this->container->getParameter('safetypay.fail.order.append');
        $redirectFailAppendField = $this->container->getParameter('safetypay.fail.order.field');

        $redirectFailData    = $redirectFailAppend
            ? array(
                $redirectFailAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();

        $failRoute = $this->generateUrl($redirectFailUrl, $redirectFailData, true);

        try {

            $formView = $this
               ->get('safetypay.form.type.wrapper')
               ->buildForm($successRoute, $failRoute, $safetyPayTransaction, $paymentMethod)
               ->getForm()
               ->createView();

        } catch (PaymentException $e) {
            return $this->redirect($this->generateUrl('cart_fail', array('order_id' => $this->get('payment.bridge')->getOrderId())));
        }
        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        return array(

            'safetypay_form' => $formView,
        );
    }

    /**
     * Post url, that allow your system to receive Automatic Payment Notifications
     * it is configure in you Safety Merchant Management System (MMS)
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("POST")
     */
    public function confirmAction(Request $request)
    {
        $paymentMethod = new SafetypayMethod();

        $postData = array(
            'MerchantReferenceNo' => $request->request->get('MerchantReferenceNo'),
            'ApiKey'              => $request->request->get('Apikey'),
            'RequestDateTime'     => $request->request->get('RequestDateTime'),
            'Signature'           => $request->request->get('Signature')
        );

        $this->get('safetypay.manager')->confirmPayment($paymentMethod, $postData);

        return new Response();
    }
}
