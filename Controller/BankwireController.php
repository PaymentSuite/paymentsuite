<?php

/**
 * BankwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BankwireBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\BankwireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * BankwireController
 */
class BankwireController extends Controller
{

    /**
     * Payment execution
     *
     * @return RedirectResponse
     *
     * @Method("GET")
     */
    public function executeAction()
    {
        $this
            ->get('bankwire.manager')
            ->processPayment();

        $redirectUrl = $this->container->getParameter('bankwire.success.route');
        $redirectAppend = $this->container->getParameter('bankwire.success.order.append');
        $redirectAppendField = $this->container->getParameter('bankwire.success.order.field');

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}