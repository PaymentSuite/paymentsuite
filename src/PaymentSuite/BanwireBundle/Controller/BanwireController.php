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

namespace PaymentSuite\BanwireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\BanwireBundle\BanwireMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * BanwireController
 */
class BanwireController extends Controller
{
    /**
     * Execute action
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function executeAction(Request $request)
    {
        /**
         * @var FormInterface $form
         */
        $form = $this
            ->get('form.factory')
            ->create('paymill_view');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            $paymentMethod = new BanwireMethod();
            $paymentMethod
                ->setCardType($data['card_type'])
                ->setCardName($data['card_name'])
                ->setCardNum($data['card_num'])
                ->setCardExpMonth($data['card_exp_month'])
                ->setCardExpYear($data['card_exp_year'])
                ->setCardSecurity($data['card_ccv2']);
            try {
                $this->get('banwire.manager')
                    ->processPayment($paymentMethod, $data['amount']);

                $redirectUrl = $this->container->getParameter('banwire.success.route');
                $redirectAppend = $this->container->getParameter('banwire.success.order.append');
                $redirectAppendField = $this->container->getParameter('banwire.success.order.field');

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('banwire.fail.route');
                $redirectAppend = $this->container->getParameter('banwire.fail.order.append');
                $redirectAppendField = $this->container->getParameter('banwire.fail.order.field');
            }

        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('banwire.fail.route');
            $redirectAppend = $this->container->getParameter('banwire.fail.order.append');
            $redirectAppendField = $this->container->getParameter('banwire.fail.order.field');
        }

        $redirectData   = $redirectAppend
            ? array(
                $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}
