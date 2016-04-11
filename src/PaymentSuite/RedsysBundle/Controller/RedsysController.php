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

namespace PaymentSuite\RedsysBundle\Controller;

use Atresmediahf\MarketplaceBundle\Entity\OrderExt;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * RedsysController
 */
class RedsysController extends Controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("GET")
     */
    public function executeAction(Request $request)
    {
        $formView = $this->get('redsys.manager')->processPayment();

        return $this->render('RedsysBundle:Redsys:process.html.twig',array(
            'redsys_form' => $formView,
        ));
    }

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     */
    public function resultAction(Request $request)
    {
        $orderId = $request->query->get('id', false);
        $order = $this->get('payment.bridge')->findOrder($orderId);

        $this->get('payment.bridge')->setOrder($order);
        $this->get('redsys.manager')->processResult($request->request->all());

        $response = new Response('OK'.$name, Response::HTTP_OK);
    }

    /**
     * Payment success action
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function okAction(Request $request)
    {
        $orderId = $request->query->get('order_id', false);

        return $this->render('RedsysBundle:Frontend:success.html.twig', array(
            'orderId' => $orderId,
        ));
    }

    /**
     * Payment fail action
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function koAction(Request $request)
    {
        $orderId = $request->query->get('order_id', false);

        return $this->render('RedsysBundle:Frontend:fail.html.twig', array(
            'orderId' => $orderId,
        ));
    }
}
