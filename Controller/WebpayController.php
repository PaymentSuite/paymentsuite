<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle\Controller;

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

        $tbkOrdenCompra = $paymentBridge->getOrderId();
        $tbkIdSesion = $this->get('webpay.manager')->processPayment();

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
        $paymentMethod->setTransactionId($tbkIdSesion);
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
        $status='ACEPTADO';
        $paymentMethod = new WebpayMethod();

        try {
            $this->get('webpay.manager')->confirmPayment($paymentMethod, $request->request->all());
        } catch (PaymentOrderNotFoundException $e) {
            $status = 'RECHAZADO1';
        } catch (WebpayMacCheckException $e) {
            $status = 'RECHAZADO2';
        } catch (PaymentAmountsNotMatchException $e) {
            $status = 'RECHAZADO3';
        } catch (PaymentException $e) {
        }

        return new Response($status);
    }
}
