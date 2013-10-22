<?php

namespace Scastells\BanwireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\BanwireBundle\BanwireMethod;

/**
 * BanwireController
 */
class BanwireController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('banwire_view');
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
            try{
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
