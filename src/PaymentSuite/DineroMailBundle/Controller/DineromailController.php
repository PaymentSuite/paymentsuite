<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\DineroMailBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PaymentSuite\DineromailBundle\DineromailMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;

/**
 * DineromailController
 *
 */
class DineromailController extends Controller
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
    public function executeAction()
    {
        $paymentMethod = new DineromailMethod;
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

        $dineromailTransactionId = $paymentBridge->getOrderId() . '#' . date('Ymdhis');
        $paymentMethod->setDineromailTransactionId($dineromailTransactionId);

        /**
         * Loading success route for returning from dineroMail
         */
        $redirectSuccessUrl = $this->container->getParameter('dineromail.success.route');
        $redirectSuccessAppend = $this->container->getParameter('dineromail.success.order.append');
        $redirectSuccessAppendField = $this->container->getParameter('dineromail.success.order.field');

        $redirectSuccessData    = $redirectSuccessAppend
                                ? array(
                                    $redirectSuccessAppendField => $this->get('payment.bridge')->getOrderId(),
                                )
                                : array();

        $successRoute = $this->generateUrl($redirectSuccessUrl, $redirectSuccessData, true);

        /**
         * Loading fail route for returning from dineroMail
         */
        $redirectFailUrl = $this->container->getParameter('dineromail.fail.route');
        $redirectFailAppend = $this->container->getParameter('dineromail.fail.order.append');
        $redirectFailAppendField = $this->container->getParameter('dineromail.fail.order.field');

        $redirectFailData    = $redirectFailAppend
                                ? array(
                                    $redirectFailAppendField => $this->get('payment.bridge')->getOrderId(),
                                )
                                : array();

        $failRoute = $this->generateUrl($redirectFailUrl, $redirectFailData, true);

        $this->get('payment.event.dispatcher')->notifyPaymentOrderDone($paymentBridge, $paymentMethod);

        /**
         * Build form
         */
        $formView = $this
            ->get('dineromail.form.type.wrapper')
            ->buildForm($successRoute, $failRoute, $dineromailTransactionId)
            ->getForm()
            ->createView();

        return array(

            'dineromail_form' => $formView,
        );
    }

    /**
     * Payment process
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("POST")
     */
    public function processAction(Request $request)
    {
        if ($notificacion = $request->request->get('Notificacion')) {
            $xml = simplexml_load_string($notificacion);
            if ($xml instanceof \SimpleXMLElement) {
                switch ((string) $xml->tiponotificacion) {
                    case '1':
                        foreach ($xml->operaciones->operacion as $oper) {
                            $this->get('dineromail.manager')->checkTransactionStatus((string) $oper->id);
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return new Response();
    }
}
