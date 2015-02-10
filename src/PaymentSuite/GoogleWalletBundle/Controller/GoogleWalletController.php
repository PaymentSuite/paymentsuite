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

namespace PaymentSuite\GoogleWalletBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * Class GoogleWalletController
 *
 */
class GoogleWalletController extends Controller
{
    /**
     * Payment callback
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     * @throws PaymentException
     *
     * @Method("POST")
     */
    public function callbackAction(Request $request)
    {
        $params = array();
        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
        }
        $response = $params['response'];

        try {
            $googlewalletManager = $this->get('googlewallet.manager');
            $googlewalletManager->processPayment($response);

            $redirectUrl = $this->container->getParameter('googlewallet.success.route');
            $redirectAppend = $this->container->getParameter('googlewallet.success.order.append');
            $redirectAppendField = $this->container->getParameter('googlewallet.success.order.field');

        } catch (PaymentException $e) {

            /**
             * Must redirect to fail route
             */
            $redirectUrl = $this->container->getParameter('googlewallet.fail.route');
            $redirectAppend = $this->container->getParameter('googlewallet.fail.order.append');
            $redirectAppendField = $this->container->getParameter('googlewallet.fail.order.field');

            throw $e;
        }
        $redirectData   = $redirectAppend ? array($redirectAppendField => $this->get('payment.bridge')->getOrderId()) : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
