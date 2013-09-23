<?php

/**
 * TransferenceBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package TransferenceBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\TransferenceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * TransferenceController
 */
class TransferenceController extends Controller
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
            ->get('transference.manager')
            ->processPayment();

        $redirectUrl = $this->container->getParameter('transference.success.route');
        $redirectAppend = $this->container->getParameter('transference.success.order.append');
        $redirectAppendField = $this->container->getParameter('transference.success.order.field');

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}