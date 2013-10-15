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
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\PagosonlineGatewayBundle\PagosonlineGatewayMethod;
use Symfony\Component\HttpFoundation\Response;


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

        $paymentMethod->setReference($paymentBridge->getOrderId() . '#' . date('Ymdhis'));
        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        /*
         * url send to pagosonline
         */
        $redirectResponseUrl = $this->container->getParameter('pagosonline_gateway.controller.route.response.name');

        $responseRoute = $this->generateUrl($redirectResponseUrl, array(), true);

        $redirectConfirmUrl = $this->container->getParameter('pagosonline_gateway.controller.route.confirmation.name');

        $confirmRoute = $this->generateUrl($redirectConfirmUrl, array(), true);
        /**
         * Build form
         */
        $formView = $this
            ->get('pagosonline_gateway.form.type.wrapper')
            ->buildForm($responseRoute, $confirmRoute)
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
     */
    public function confirmationAction(Request $request)
    {
        $signature = $request->request->get('firma');
        $status_pol = $request->request->get('estado_pol');
        $currency = $request->request->get('moneda');
        $value = $request->request->get('valor');
        $orderRef = $request->request->get('ref_venta');
        $userId = $request->request->get('usuario_id');
        $key = $this->container->getParameter('pagosonline_gateway.key');
        $signatureHash = md5($key.'~'.$userId.'~'.$orderRef.'~'.$value.'~'.$currency.'~'.$status_pol);
        $referencePol = $request->request->get('ref_pol');

        $paymentBridge = $this->get('payment.bridge');

        $trans = $this->getDoctrine()->getRepository('PagosonlineGatewayBridgeBundle:PagosonlineGatewayOrderTransaction')->find($orderRef);
        $trans->getOrder()->getId();

        //save values
        $paymentMethod = new PagosonlineGatewayMethod();
        $paymentMethod->setPagosonlineGatewayTransactionId($referencePol);
        $paymentMethod->setPagosonlineGatewayReference($referencePol);
        $paymentMethod->setReference($orderRef);
        $paymentMethod->setStatus($status_pol);
        $order = $paymentBridge->findOrder($trans->getOrder()->getId());
        $paymentBridge->setOrder($order);
        if (strtoupper($signatureHash) == $signature) {
            if ($status_pol == 4) {
                $this->get('payment.event.dispatcher')->notifyPaymentOrderSuccess($paymentBridge, $paymentMethod);

            } elseif ($status_pol == 5 || $status_pol == 6) {
                $this->get('payment.event.dispatcher')->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
            }
        }
        return new Response();
    }

    /**
     * Payment response
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     */
    public function responseAction(Request $request)
    {

        $status_pol = $request->get('estado_pol');
        $orderRef = $request->get('ref_venta');
        $order = explode('#',$orderRef);
        $orderId = $order[0];

        if($status_pol == 5 || $status_pol == 6) { //canceled OR rejected

            $redirectUrl = $this->container->getParameter('pagosonline_gateway.fail.route');
            $redirectFailAppend = $this->container->getParameter('pagosonline_gateway.fail.order.append');
            $redirectFailAppendField = $this->container->getParameter('pagosonline_gateway.fail.order.field');
            $redirectData    = $redirectFailAppend
                ? array(
                    $redirectFailAppendField => $orderId,
                )
                : array();

        } elseif($status_pol ==  4) {

            $redirectUrl = $this->container->getParameter('pagosonline_gateway.success.route');
            $redirectSuccessAppend = $this->container->getParameter('pagosonline_gateway.success.order.append');
            $redirectSuccessAppendField = $this->container->getParameter('pagosonline_gateway.success.order.field');

            $redirectData    = $redirectSuccessAppend
                ? array(
                    $redirectSuccessAppendField => $orderId,
                )
                : array();
        }
        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
