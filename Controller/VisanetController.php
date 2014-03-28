<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Controller;

use PaymentSuite\PayuBundle\Model\AuthorizationAndCaptureTransaction;
use PaymentSuite\PayuBundle\Model\Order;
use PaymentSuite\PayuBundle\Model\SubmitTransactionRequest;
use PaymentSuite\PayuBundle\PayuRequestTypes;
use PaymentSuite\PayuBundle\PayuTransactionTypes;
use PaymentSuite\PayuBundle\Services\PayuManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PayuBundle\VisanetMethod;

/**
 * VisanetController
 */
class VisanetController extends Controller
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
        $paymentMethod = new VisanetMethod();
        $paymentBridge = $this->get('payment.bridge');

        // New order from cart must be created right here
        $this->get('payment.event.dispatcher')->notifyPaymentOrderLoad($paymentBridge, $paymentMethod);

        if (!$paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException;
        }

        $reference = $paymentBridge->getExtraData()['reference'];
        $amount = $paymentBridge->getAmount();
        $currency = $paymentBridge->getCurrency();

        /** @var $manager PayuManager */
        $manager = $this->get('payu.manager');
        /** @var $order Order */
        $order = $this->get('payu.factory.order')->create();
        $order->setReferenceCode($reference);
        $order->setDescription($paymentBridge->getExtraData()['description']);
        $order->setSignature($manager->getSignature($reference, $amount, $currency));
        /** @var $transaction AuthorizationAndCaptureTransaction */
        $transaction = $this->get('payu.factory.payutransaction')->create(PayuTransactionTypes::TYPE_AUTHORIZATION_AND_CAPTURE);
        $transaction->setOrder($order);
        /** @var $request SubmitTransactionRequest */
        $request = $this->get('payu.factory.payurequest')->create(PayuRequestTypes::TYPE_SUBMIT_TRANSACTION);
        $request->setTransaction($transaction);

        $response = $manager->processAuthorizationAndCapture($request);
        $orderId = $paymentBridge->getOrderId();
/*        $sessionId = $this->get('Payu.manager')->processPayment();

        // Generate ok and fail URLs
        $successRoute = $this->container->getParameter('Payu.success.route');
        $successRouteAppend = $this->container->getParameter('Payu.success.order.append');
        $successRouteAppendField = $this->container->getParameter('Payu.success.order.field');
        $successRouteData = $successRouteAppend ? array($successRouteAppendField => $orderId) : array();
        $successUrl = $this->generateUrl($successRoute, $successRouteData, true);

        $failRoute = $this->container->getParameter('Payu.fail.route');
        $failRouteAppend = $this->container->getParameter('Payu.fail.order.append');
        $failRouteAppendField = $this->container->getParameter('Payu.fail.order.field');
        $failRouteData = $failRouteAppend ? array($failRouteAppendField => $orderId) : array();
        $failUrl = $this->generateUrl($failRoute, $failRouteData, true);

        // Notify payment done
        $paymentMethod->setSessionId($sessionId);
        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        // Generate form
        $formView = $this
            ->get('Payu.form.type.wrapper')
            ->buildForm($sessionId, $successUrl, $failUrl)
            ->getForm()
            ->createView();
*/

        return array(
            'visanet_url' => '',
            'visanet_eticket' => ''
        );
    }
}
