<?php

namespace Scastells\BanwireGatewayBundle\Controller;

use Scastells\BanwireGatewayBundle\Encryptor\RC4;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\BanwireGatewayBundle\BanwireGatewayMethod;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BanwireGatewayController
 */
class BanwireGatewayController extends controller
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
        $paymentMethod = new BanwireGatewayMethod();
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
         * Loading success route for returning from banwire
         */
        $redirectSuccessUrl = $this->container->getParameter('banwire_gateway.success.route');
        $redirectSuccessAppend = $this->container->getParameter('banwire_gateway.success.order.append');
        $redirectSuccessAppendField = $this->container->getParameter('banwire_gateway.success.order.field');

        $redirectSuccessData    = $redirectSuccessAppend
            ? array(
                $redirectSuccessAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();

        $successRoute = $this->generateUrl($redirectSuccessUrl, $redirectSuccessData, true);

        $paymentMethod->setReference($paymentBridge->getOrderId() . '#' . date('Ymdhis'));
        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        /*
         * url send to banwire
         */
        $redirectResponseUrl = $this->container->getParameter('banwire_gateway.controller.route.response.name');

        $responseRoute = $this->generateUrl($redirectResponseUrl, array('reference'=> $paymentMethod->getReference()), true);


        /**
         * Build form
         */
        $formView = $this
            ->get('banwire_gateway.form.type.wrapper')
            ->buildForm($responseRoute)
            ->getForm($successRoute)
            ->createView();
        return array(

            'banwire_gateway_form' => $formView,
        );
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
        $paymentMethod = new BanwiregatewayMethod();
        $paymentBridge = $this->get('payment.bridge');

        $key = $request->request->get('key');
        $reference = $request->request->get('reference');//reference send to banwire in executeAction url_response
        $codeAuth = $request->request->get('code_auth');
        $response = $request->request->get('response');
        $codeError = $request->request->get('code');
        $banwireId = $request->request->get('id');

        $infoLog = array(
            'key'           => $key,
            'reference'     => $reference,
            'code_auth'     => $codeAuth,
            'response'      => $response,
            'code_error'    => $codeError,
            'banwire_id'    => $banwireId,
            'action'        => 'BanwireResponseAction'
        );

        $this->get('logger')->addInfo($paymentMethod->getPaymentName(),$infoLog);

        $encrypt = new RC4($this->container->getParameter('banwire_gateway.cps'));

        $trans = $this->getDoctrine()->getRepository('BanwireGatewayBridgeBundle:BanwireGatewayOrderTransaction')
            ->findOneBy(array('reference' => $reference));

        $order = $paymentBridge->findOrder($trans->getOrder()->getId());
        $paymentBridge->setOrder($order);
        $paymentMethod->setBanwireGatewayTransactionId($codeAuth);
        $paymentMethod->setReference($reference);
        $paymentMethod->setCodeError($codeError);
        $paymentMethod->setBanwireId($banwireId);
        $string = implode('', $request->request->all());
        if ($encrypt->encrypt($string) == $key)
        {
            if ($response == 'ok') {

                if ($paymentBridge->getAmount() - $request->request->get('monto') != 0) {

                    $this->get('payment.event.dispatcher')->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
                    $redirectUrl = $this->container->getParameter('banwire_gateway.fail.route');
                    $redirectFailAppend = $this->container->getParameter('banwire_gateway.fail.order.append');
                    $redirectFailAppendField = $this->container->getParameter('banwire_gateway.fail.order.field');
                    $redirectData    = $redirectFailAppend
                        ? array(
                            $redirectFailAppendField => $trans->getOrder()->getId(),
                        )
                        : array();
                } else {

                    $this->get('payment.event.dispatcher')->notifyPaymentOrderSuccess($paymentBridge, $paymentMethod);

                    $redirectUrl = $this->container->getParameter('banwire_gateway.success.route');
                    $redirectSuccessAppend = $this->container->getParameter('banwire_gateway.success.order.append');
                    $redirectSuccessAppendField = $this->container->getParameter('banwire_gateway.success.order.field');

                    $redirectData    = $redirectSuccessAppend
                        ? array(
                            $redirectSuccessAppendField => $trans->getOrder()->getId(),
                        )
                        : array();
                }

            } else {

                $this->get('payment.event.dispatcher')->notifyPaymentOrderFail($paymentBridge, $paymentMethod);

                $redirectUrl = $this->container->getParameter('banwire_gateway.fail.route');
                $redirectFailAppend = $this->container->getParameter('banwire_gateway.fail.order.append');
                $redirectFailAppendField = $this->container->getParameter('banwire_gateway.fail.order.field');
                $redirectData    = $redirectFailAppend
                    ? array(
                        $redirectFailAppendField => $trans->getOrder()->getId(),
                    )
                    : array();
            }

        } else {

            $this->get('payment.event.dispatcher')->notifyPaymentOrderFail($paymentBridge, $paymentMethod);

            $redirectUrl = $this->container->getParameter('banwire_gateway.fail.route');
            $redirectFailAppend = $this->container->getParameter('banwire_gateway.fail.order.append');
            $redirectFailAppendField = $this->container->getParameter('banwire_gateway.fail.order.field');
            $redirectData    = $redirectFailAppend
                ? array(
                    $redirectFailAppendField => $trans->getOrder()->getId(),
                )
                : array();
        }
        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}