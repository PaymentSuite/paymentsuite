<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package DineromailBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\DineromailBundle\Controller;

use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mmoreram\DineromailBundle\DineromailMethod;


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
    public function executeAction(Request $request)
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


        /**
         * Loading success route for returning from dineroMail
         */
        $redirectSuccessUrl = $this->container->getParameter('paymill.success.route');
        $redirectSuccessAppend = $this->container->getParameter('paymill.success.order.append');
        $redirectSuccessAppendField = $this->container->getParameter('paymill.success.order.field');

        $redirectSuccessData    = $redirectSuccessAppend
                                ? array(
                                    $redirectSuccessAppendField => $this->get('payment.bridge')->getOrderId(),
                                )
                                : array();

        $successRoute = $this->generateUrl($redirectSuccessUrl, $redirectSuccessData, true);


        /**
         * Loading fail route for returning from dineroMail
         */
        $redirectFailUrl = $this->container->getParameter('paymill.fail.route');
        $redirectFailAppend = $this->container->getParameter('paymill.fail.order.append');
        $redirectFailAppendField = $this->container->getParameter('paymill.fail.order.field');

        $redirectFailData    = $redirectFailAppend
                                ? array(
                                    $redirectFailAppendField => $this->get('payment.bridge')->getOrderId(),
                                )
                                : array();

        $failRoute = $this->generateUrl($redirectFailUrl, $redirectFailData, true);

        /**
         * Build form
         */
        $formView = $this
            ->get('dineromail.form.type.wrapper')
            ->buildForm($successRoute, $failRoute)
            ->getForm()
            ->createView();

        return array(

            'dineromail_form' => $formView,
        );
    }
}