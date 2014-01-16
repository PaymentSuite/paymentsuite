<?php

/**
 * FreePaymentBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package FreePaymentBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\FreePaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PaymentSuite\FreePaymentBundle\FreePaymentMethod;

/**
 * PaymillController
 */
class FreePaymentController extends Controller
{

    /**
     * Free Payment execution
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeAction()
    {
        $freePaymentMethod = new FreePaymentMethod;

        $this
            ->get('freepayment.manager')
            ->processPayment($freePaymentMethod);

        $redirectUrl = $this->container->getParameter('freepayment.success.route');
        $redirectAppend = $this->container->getParameter('freepayment.success.order.append');
        $redirectAppendField = $this->container->getParameter('freepayment.success.order.field');

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
