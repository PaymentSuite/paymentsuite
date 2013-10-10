<?php

/**
 * PagosonlineGateway for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PagosonlineGatewayBundle
 *
 */

namespace Scastells\PagosonlineGatewayBundle\Controller;

use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\PagosonlineGatewayBundle\PagosonlineGatewayMethod;


/**
 * PagosonlineGatewayController
 *
 */
class PagosonlineGatewayController extends Controller
{

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     * @Template()
     */
    public function executeAction(Request $request)
    {
        $paymentMethod = new PagosonlineGatewayMethod();
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


        /**
         * Loading success route for returning from pagosonline
         */
        $redirectSuccessUrl = $this->container->getParameter('pagosonline_gateway.success.route');
        $redirectSuccessAppend = $this->container->getParameter('pagosonline_gateway.success.order.append');
        $redirectSuccessAppendField = $this->container->getParameter('pagosonline_gateway.success.order.field');

        $redirectSuccessData    = $redirectSuccessAppend
                                ? array(
                                    $redirectSuccessAppendField => $this->get('payment.bridge')->getOrderId(),
                                )
                                : array();

        $successRoute = $this->generateUrl($redirectSuccessUrl, $redirectSuccessData, true);


        /**
         * Loading fail route for returning from pagosonline_gateway
         */
        $redirectFailUrl = $this->container->getParameter('pagosonline_gateway.fail.route');
        $redirectFailAppend = $this->container->getParameter('pagosonline_gateway.fail.order.append');
        $redirectFailAppendField = $this->container->getParameter('pagosonline_gateway.fail.order.field');

        $redirectFailData    = $redirectFailAppend
                                ? array(
                                    $redirectFailAppendField => $this->get('payment.bridge')->getOrderId(),
                                )
                                : array();

        $failRoute = $this->generateUrl($redirectFailUrl, $redirectFailData, true);

        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        $redirectResponseUrl = $this->container->getParameter('pagosonline_gateway.controller.route.response.name');

        $responseRoute = $this->generateUrl($redirectResponseUrl, array(), true);
        /**
         * Build form
         */
        $formView = $this
            ->get('pagosonline_gateway.form.type.wrapper')
            ->buildForm($successRoute, $responseRoute, $failRoute)
            ->getForm($successRoute)
            ->createView();

        return array(

            'pagosonline_gateway_form' => $formView,
        );
    }


    /**
     * Payment reponse
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Template()
     */
    public function confirmationAction(Request $request)
    {
        $signature = $request->request->get('firma');
        $status_pol = $request->request->get('estado_pol');
        $currency = $request->request->get('moneda');
        $value = $request->request->get('valor');
        $orderId = $request->request->get('ref_venta');
        $userId = $request->request->get('usuario_id');
        $key = $this->container->getParameter('pagosonline_gateway.key');
        $signatureHash = md5($key.'~'.$userId.'~'.$orderId.'~'.$value.'~'.$currency.'~'.$status_pol);

        $paymentBridge = $this->get('payment.bridge');
        $paymentMethod = new PagosonlineGatewayMethod();

        if (strtoupper($signatureHash) == $signature) {
            if ($status_pol == 4) {

                $this->get('payment.event.dispatcher')->notifyPaymentOrderSuccessEvent($paymentBridge, $paymentMethod);

            } elseif ($status_pol == 5 || $status_pol == 6) {

                $this->get('payment.event.dispatcher')->notifyPaymentOrderFailEvent($paymentBridge, $paymentMethod);
            }
        }

    }

    /**
     * Payment reponse
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Template()
     */
    public function responseAction(Request $request)
    {
        $status_pol = $request->request->get('estado_pol');

        if($status_pol == 5 || $status_pol == 6) { //canceled OR rejected

            $redirectUrl = $this->container->getParameter('pagosonline_gateway.fail.route');
            $redirectFailAppend = $this->container->getParameter('pagosonline_gateway.fail.order.append');
            $redirectFailAppendField = $this->container->getParameter('pagosonline_gateway.fail.order.field');
            $redirectData    = $redirectFailAppend
                ? array(
                    $redirectFailAppendField => $this->get('payment.bridge')->getOrderId(),
                )
                : array();

        } elseif($status_pol ==  4) {

            $redirectUrl = $this->container->getParameter('pagosonline_gateway.success.route');
            $redirectSuccessAppend = $this->container->getParameter('pagosonline_gateway.success.order.append');
            $redirectSuccessAppendField = $this->container->getParameter('pagosonline_gateway.success.order.field');

            $redirectData    = $redirectSuccessAppend
                ? array(
                    $redirectSuccessAppendField => $this->get('payment.bridge')->getOrderId(),
                )
                : array();
        }
        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
