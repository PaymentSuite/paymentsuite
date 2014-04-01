<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Controller;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PayuBundle\Model\AdditionalValue;
use PaymentSuite\PayuBundle\Model\AuthorizationAndCaptureTransaction;
use PaymentSuite\PayuBundle\Model\Order;
use PaymentSuite\PayuBundle\Model\SubmitTransactionRequest;
use PaymentSuite\PayuBundle\Model\User;
use PaymentSuite\PayuBundle\PayuAdditionalValueTypes;
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

        /** @var $manager PayuManager */
        $manager = $this->get('payu.manager');

        $reference = $paymentBridge->getExtraData()['reference'];
        $amount = $paymentBridge->getAmount();
        $currency = $paymentBridge->getCurrency();
        $userEmail = $paymentBridge->getExtraData()['customer_email'];

        /** @var $buyer User */
        $buyer = $this->get('payu.factory.user')->create();
        $buyer->setEmailAddress($userEmail);
        /** @var $additionalValue AdditionalValue */
        $additionalValue = $this->get('payu.factory.additionalvalue')->create();
        $additionalValue->setValue($amount);
        $additionalValue->setCurrency($currency);
        /** @var $order Order */
        $order = $this->get('payu.factory.order')->create();
        $order->setReferenceCode($reference);
        $order->setDescription($paymentBridge->getExtraData()['description']);
        $order->setSignature($manager->getSignature($reference, $amount, $currency));
        $order->setBuyer($buyer);
        $order->setAdditionalValues($additionalValue, PayuAdditionalValueTypes::TYPE_TX_VALUE);
        /** @var $transaction AuthorizationAndCaptureTransaction */
        $transaction = $this->get('payu.factory.payutransaction')->create(PayuTransactionTypes::TYPE_AUTHORIZATION_AND_CAPTURE);
        $transaction->setOrder($order);
        $transaction->setPaymentMethod('VISA');
        $transaction->setSource('WEB');
        /** @var $request SubmitTransactionRequest */
        $request = $this->get('payu.factory.payurequest')->create(PayuRequestTypes::TYPE_SUBMIT_TRANSACTION);
        $request->setTransaction($transaction);

        try {
            $redirectRoute = '';
            $redirectData = '';
            $response = $manager->processPaymentRequest($request);

            $paymentMethod->setTransaction($response);
            $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

            switch ($response->getState()) {
                case 'APPROVED':
                    $this->get('payment.event.dispatcher')->notifyPaymentOrderSuccess($paymentBridge, $paymentMethod);
                    $redirectRoute = $this->container->getParameter('payu.success.route');
                    $redirectData = $this->container->getParameter('payu.success.order.append')
                        ? array($this->container->getParameter('payu.success.order.field') => $paymentBridge->getOrderId())
                        : array();
                    break;
                case 'PENDING':
                    if (!$response->getExtraParameters()['VISANET_PE_URL'] || !$response->getTrazabilityCode()) {
                        $redirectRoute = $this->container->getParameter('payu.success.route');
                        $redirectData = $this->container->getParameter('payu.success.order.append')
                            ? array($this->container->getParameter('payu.success.order.field') => $paymentBridge->getOrderId())
                            : array();
                    }
                    break;
                default:
                    $this->get('payment.event.dispatcher')->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
                    $redirectRoute = $this->container->getParameter('payu.fail.route');
                    $redirectData = $this->container->getParameter('payu.fail.order.append')
                        ? array($this->container->getParameter('payu.fail.order.field') => $paymentBridge->getOrderId())
                        : array();
                    break;
            }
        } catch (PaymentException $e) {
            $redirectRoute = $this->container->getParameter('payu.fail.route');
            $redirectData = $this->container->getParameter('payu.fail.order.append')
                ? array($this->container->getParameter('payu.fail.order.field') => $paymentBridge->getOrderId())
                : array();
        }

        if ($redirectRoute){

            return $this->redirect($this->generateUrl($redirectRoute, $redirectData));
        }

        return array(
            'visanet_url' => $response->getExtraParameters()['VISANET_PE_URL'],
            'visanet_eticket' => $response->getTrazabilityCode()
        );
    }
}
