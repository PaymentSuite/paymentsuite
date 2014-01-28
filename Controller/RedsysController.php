<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2014
 */
namespace PaymentSuite\RedsysBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PaymentSuite\RedsysBundle\RedsysMethod;


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
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {

        return $this->get('redsys.manager')->processPayment();
    }

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeTransaction(Request $request)
    {

        try {
            $this
                ->get('redsys.manager')
                ->processTransaction();

            $redirectUrl = $this->container->getParameter('redsys.success.route');
            $redirectAppend = $this->container->getParameter('redsys.success.order.append');
            $redirectAppendField = $this->container->getParameter('redsys.success.order.field');


        } catch (PaymentException $e) {

            /**
             * Must redirect to fail route
             */
            $redirectUrl = $this->container->getParameter('redsys.fail.route');
            $redirectAppend = $this->container->getParameter('redsys.fail.order.append');
            $redirectAppendField = $this->container->getParameter('redsys.fail.order.field');
        }

        $redirectData   = $redirectAppend
            ? array(
                $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}