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

namespace PaymentSuite\PagosOnlineGatewayBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PaymentSuite\PagosonlineGatewayBundle\PagosonlineGatewayMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;

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

            throw new PaymentOrderNotFoundException();
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
     *
     */
    public function confirmationAction(Request $request)
    {
        $paymentMethod = new PagosonlineGatewayMethod();
        $paymentBridge = $this->get('payment.bridge');

        $signature = $request->request->get('firma');
        $statusPol = $request->request->get('estado_pol');
        $currency = $request->request->get('moneda');
        $value = $request->request->get('valor');
        $orderRef = $request->request->get('ref_venta');
        $userId = $request->request->get('usuario_id');
        $key = $this->container->getParameter('pagosonline_gateway.key');
        $signatureHash = md5($key.'~'.$userId.'~'.$orderRef.'~'.$value.'~'.$currency.'~'.$statusPol);
        $referencePol = $request->request->get('ref_pol');

        $infoLog = array(
            'firma'         => $signature,
            'estado_pol'    => $statusPol,
            'moneda'        => $currency,
            'valor'         => $value,
            'ref_venda'     => $orderRef,
            'usuario_id'    => $userId,
            'hash'          => $signatureHash,
            'ref_pol'       => $referencePol,
            'action'        => 'confirmationAction'
        );

        $this->get('logger')->addInfo($paymentMethod->getPaymentName(), $infoLog);

        //@TODO use notifyPaymentOrderLoad for check order
        $orderRefPol = explode("#",$orderRef);
        $orderId = $orderRefPol[0];

        $paymentMethod->setPagosonlineGatewayTransactionId($referencePol);
        $paymentMethod->setPagosonlineGatewayReference($referencePol);
        $paymentMethod->setReference($orderRef);
        $paymentMethod->setStatus($statusPol);

        $order = $paymentBridge->findOrder($orderId);
        $paymentBridge->setOrder($order);

        $orderPaidStatus = 4;

        if (strtoupper($signatureHash) == $signature) {

            if ($statusPol == $orderPaidStatus) {

                $this->get('payment.event.dispatcher')->notifyPaymentOrderSuccess($paymentBridge, $paymentMethod);

            } else {

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

        $statusPol = $request->query->get('estado_pol');
        $orderRef = $request->query->get('ref_venta');

        //@TODO use notifyPaymentOrderLoad for check order
        $orderRefPol = explode("#",$orderRef);
        $orderId = $orderRefPol[0];
        $orderPaidStatus = 4;

        if ($statusPol == $orderPaidStatus) {

            $redirectUrl = $this->container->getParameter('pagosonline_gateway.success.route');
            $redirectSuccessAppend = $this->container->getParameter('pagosonline_gateway.success.order.append');
            $redirectSuccessAppendField = $this->container->getParameter('pagosonline_gateway.success.order.field');

            $redirectData    = $redirectSuccessAppend
                ? array(
                    $redirectSuccessAppendField => $orderId,
                )
                : array();
        } else {

            $redirectUrl = $this->container->getParameter('pagosonline_gateway.fail.route');
            $redirectFailAppend = $this->container->getParameter('pagosonline_gateway.fail.order.append');
            $redirectFailAppendField = $this->container->getParameter('pagosonline_gateway.fail.order.field');
            $redirectData    = $redirectFailAppend
                ? array(
                    $redirectFailAppendField => $orderId,
                )
                : array();
        }

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
