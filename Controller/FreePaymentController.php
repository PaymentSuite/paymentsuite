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
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\FreePaymentBundle\PaymillMethod;


/**
 * PaymillController
 */
class PaymillController extends Controller
{

    /**
     * Free Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     */
    public function executeAction(Request $request)
    {
        $this
            ->get('freepayment.manager')
            ->processPayment();

        $redirectUrl = $this->container->getParameter('freepayment.success.route');
        $redirectAppend = $this->container->getParameter('freepayment.success.order.append');
        $redirectAppendField = $this->container->getParameter('freepayment.success.order.field');

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
