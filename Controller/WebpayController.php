<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle\Controller;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PaymentSuite\WebpayBundle\WebpayMethod;
use Symfony\Component\HttpFoundation\Response;

/**
 * WebpayController
 */
class WebpayController extends Controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("POST")
     *
     * @Template()
     */
    public function executeAction(Request $request)
    {
        $paymentMethod = new WebpayMethod();
        $paymentBridge = $this->get('payment.bridge');

        // New order from cart must be created right here
        $this->get('payment.event.dispatcher')->notifyPaymentOrderLoad($paymentBridge, $paymentMethod);

        if (!$paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException;
        }

        $tbkOrdenCompra = $paymentBridge->getOrderId();
        $tbkIdSesion = $tbkOrdenCompra . date('Ymdhis');
        $tbkTotal = floor($paymentBridge->getAmount() * 100);

        // Generate session log file for KCC
        $file = fopen($this->container->getParameter('webpay.kcc.path').'/log/datos'.$tbkIdSesion.'.log', 'w');
        $line = $tbkTotal . ';' . $tbkOrdenCompra;
        fwrite($file, $line);
        fclose($file);

        // Generate ok and fail URLs
        $successRoute = $this->container->getParameter('webpay.success.route');
        $successRouteAppend = $this->container->getParameter('webpay.success.order.append');
        $successRouteAppendField = $this->container->getParameter('webpay.success.order.field');
        $successRouteData = $successRouteAppend ? array($successRouteAppendField => $tbkOrdenCompra) : array();
        $successUrl = $this->generateUrl($successRoute, $successRouteData, true);

        $failRoute = $this->container->getParameter('webpay.fail.route');
        $failRouteAppend = $this->container->getParameter('webpay.fail.order.append');
        $failRouteAppendField = $this->container->getParameter('webpay.fail.order.field');
        $failRouteData = $failRouteAppend ? array($failRouteAppendField => $tbkOrdenCompra) : array();
        $failUrl = $this->generateUrl($failRoute, $failRouteData, true);

        // Notify payment done
        $paymentMethod->setReference($tbkIdSesion);
        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        // Generate form
        $formView = $this
            ->get('webpay.form.type.wrapper')
            ->buildForm($tbkIdSesion, $successUrl, $failUrl)
            ->getForm()
            ->createView();

        return array('webpay_form' => $formView);
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
        $orderRefPol = explode("#", $orderRef);
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
        $orderRefPol = explode("#", $orderRef);
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
