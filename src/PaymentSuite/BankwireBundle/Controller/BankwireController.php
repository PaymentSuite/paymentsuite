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

namespace PaymentSuite\BankwireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * BankwireController
 */
class BankwireController extends Controller
{
    /**
     * Payment execution
     *
     * @return RedirectResponse
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
