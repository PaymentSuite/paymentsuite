<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle\Controller;

use PaymentSuite\WebpayBundle\Model\Normal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\WebpayBundle\WebpayMethod;
use PaymentSuite\WebpayBundle\Exception\WebpayMacCheckException;

/**
 * WebpayController
 */
class WebpayController extends Controller
{
    /**
     * Webpay accepted response
     */
    const WEBPAY_ACCEPTED = 'ACEPTADO';

    /**
     * Webpay rejected response
     */
    const WEBPAY_REJECTED = 'RECHAZADO';

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @throws \PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException
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

        $orderId = $paymentBridge->getOrderId();
        $sessionId = $this->get('webpay.manager')->processPayment();

        // Generate ok and fail URLs
        $successRoute = $this->container->getParameter('webpay.success.route');
        $successRouteAppend = $this->container->getParameter('webpay.success.order.append');
        $successRouteAppendField = $this->container->getParameter('webpay.success.order.field');
        $successRouteData = $successRouteAppend ? array($successRouteAppendField => $orderId) : array();
        $successUrl = $this->generateUrl($successRoute, $successRouteData, true);

        $failRoute = $this->container->getParameter('webpay.fail.route');
        $failRouteAppend = $this->container->getParameter('webpay.fail.order.append');
        $failRouteAppendField = $this->container->getParameter('webpay.fail.order.field');
        $failRouteData = $failRouteAppend ? array($failRouteAppendField => $orderId) : array();
        $failUrl = $this->generateUrl($failRoute, $failRouteData, true);

        // Notify payment done
        $paymentMethod->setSessionId($sessionId);
        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        // Generate form
        $formView = $this
            ->get('webpay.form.type.wrapper')
            ->buildForm($sessionId, $successUrl, $failUrl)
            ->getForm()
            ->createView();

        return array('webpay_form' => $formView);
    }

    /**
     * Payment confirmation
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("POST")
     */
    public function confirmationAction(Request $request)
    {
        $status = WebpayController::WEBPAY_ACCEPTED;
        $paymentMethod = new WebpayMethod();
        $transaction = new Normal();
        $transaction->setAccion($request->request->get('TBK_ACCION'))
            ->setCodigoAutorizacion($request->request->get('TBK_CODIGO_AUTORIZACION'))
            ->setCodigoComercio($request->request->get('TBK_CODIGO_COMERCIO'))
            ->setCodigoComercioEnc($request->request->get('TBK_CODIGO_COMERCIO_ENC'))
            ->setFechaContable($request->request->get('TBK_FECHA_CONTABLE'))
            ->setFechaExpiracion($request->request->get('TBK_FECHA_EXPIRACION'))
            ->setFechaTransaccion($request->request->get('TBK_FECHA_TRANSACCION'))
            ->setFinalNumeroTarjeta($request->request->get('TBK_FINAL_NUMERO_TARJETA'))
            ->setHoraTransaccion($request->request->get('TBK_HORA_TRANSACCION'))
            ->setIdSesion($request->request->get('TBK_ID_SESION'))
            ->setIdTransaccion($request->request->get('TBK_ID_TRANSACCION'))
            ->setMac($request->request->get('TBK_MAC'))
            ->setMonto($request->request->get('TBK_MONTO'))
            ->setNumeroCuotas($request->request->get('TBK_NUMERO_CUOTAS'))
            ->setOrdenCompra($request->request->get('TBK_ORDEN_COMPRA'))
            ->setRespuesta($request->request->get('TBK_RESPUESTA'))
            ->setTipoPago($request->request->get('TBK_TIPO_PAGO'))
            ->setVci($request->request->get('TBK_VCI'))
            ->setTipoTransaccion($request->request->get('TBK_TIPO_TRANSACCION'));
        $paymentMethod->setTransaction($transaction);

        try {
            $this->get('webpay.manager')->confirmPayment($paymentMethod, $request->request->all());
        } catch (PaymentOrderNotFoundException $e) {
            $status = WebpayController::WEBPAY_REJECTED;
        } catch (WebpayMacCheckException $e) {
            $status = WebpayController::WEBPAY_REJECTED;
        } catch (PaymentAmountsNotMatchException $e) {
            $status = WebpayController::WEBPAY_REJECTED;
        } catch (PaymentException $e) {
        }

        return new Response($status);
    }
}
