<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author David Pujadas <dpujadas@gmail.com>
 * @package DineromailBundle
 *
 * David Pujadas 2013
 */

namespace Dpujadas\DineromailBundle\Controller;

use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dpujadas\DineromailBundle\DineromailMethod;
use Symfony\Component\Routing\Generator\UrlGenerator;


/**
 * DineromailController
 *
 */
class DineromailController extends Controller
{
//    /**
//     * @param Request $request
//     *
//     */
//    public function processAction(Request $request)
//    {
//        $orderId=$request->get('id_order');
//        $order = $this->getDoctrine()
//            ->getRepository('BaseEcommerceCoreBundle:Order')
//            ->find($orderId);
//        if ($order->getUser()->getId() != $request->get('id_user')) {
//
//        }
//        $this->redirect();
//    }

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $paymentMethod = new DineromailMethod;
        $bridge = $this->get('payment.bridge');
        $this->get('payment.event.dispatcher')->notifyPaymentOrderLoad($bridge, $paymentMethod);

        $orderId = $bridge->getOrderId();
        $orderIdRouteField = $this->container->getParameter('dineromail.success.order.field');


        $dineromailSuccessUrl = $this->generateUrl(
            $this->container->getParameter('dineromail.success.route'), array($orderIdRouteField => $orderId), UrlGenerator::ABSOLUTE_URL
        );

        $dineromailFailUrl = $this->generateUrl(
            $this->container->getParameter('dineromail.fail.route'), array($orderIdRouteField => $orderId), UrlGenerator::ABSOLUTE_URL
        );

        $form = $this->get('form.factory')->createNamedBuilder(null, 'form')
            ->setAction('https://checkout.dineromail.com/CheckOut')
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($bridge->getAmount(), 2) * 100
            ))
            ->add('merchant', 'hidden', array(
                'data'  =>  $this->container->getParameter('dineromail.config.merchant')
            ))
            ->add('country_id', 'hidden', array(
                'data'  =>  $this->container->getParameter('dineromail.config.country_id')
            ))
            ->add('seller_name', 'hidden', array(
                'data'  =>   $this->container->getParameter('dineromail.config.seller_name')
            ))
            ->add('transaction_id', 'hidden', array(
                'data'  =>  $bridge->getOrderId().'#'.date('Ymdhis')
            ))
            ->add('language', 'hidden', array(
                'data'  =>  $this->container->getParameter('dineromail.config.language')
            ))
            ->add('currency', 'hidden', array(
                'data'  =>  $this->container->getParameter('dineromail.config.currency')
            ))
            ->add('payment_method_available', 'hidden', array(
                'data'  =>  $this->container->getParameter('dineromail.config.payment_method_available')
            ))
            ->add('buyer_name', 'hidden', array(
                'data'  =>  $bridge->getExtraData()['buyer_name']
            ))
            ->add('buyer_lastname', 'hidden', array(
                'data'  =>  $bridge->getExtraData()['buyer_lastname']
            ))
            ->add('buyer_email', 'hidden', array(
                'data'  =>  $bridge->getExtraData()['buyer_email']
            ))
            ->add('buyer_phone', 'hidden', array(
                'data'  =>  $bridge->getExtraData()['buyer_phone']
            ))
            ->add('header_image', 'hidden', array(
                'data'  =>  $this->container->getParameter('dineromail.config.header_image')
            ))
            ->add('ok_url', 'hidden', array(
                'data'  =>  $dineromailSuccessUrl
            ))
            ->add('error_url', 'hidden', array(
                'data'  =>  $dineromailFailUrl
            ))
            ->add('pending_url', 'hidden', array(
                'data'  =>  $dineromailSuccessUrl
            ))
            ->getForm();

        return $this->render('DineromailBundle:Dineromail:view.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}