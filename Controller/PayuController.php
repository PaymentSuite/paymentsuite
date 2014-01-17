<?php

namespace Scastells\PayuBundle\Controller;

use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\PayuBundle\PayUMethod;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PayuController
 */
class PayuController extends controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @throws PaymentOrderNotFoundException
     * @return RedirectResponse
     *
     * @Method("POST")
     * @Template()
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('payu_view');
        $form->handleRequest($request);


        if ($form->isValid()) {

            $data = $form->getData();


            $paymentMethod = new PayUMethod();
            $paymentMethod
                ->setCardType($data['card_type']);
            try{
                $responsePayU = $this->get('payu.manager')
                    ->processPayment($paymentMethod, $data['amount']);

                if (is_array($responsePayU)) {
                    $redirectUrl = $this->container->getParameter('payu.response.route');
                    $redirectAppendField = $this->container->getParameter('payu.payment_response.visanet_e');
                    $redirectAppendFieldUrl = $this->container->getParameter('payu.payment_response.visanet_url');
                    $redirectData   =  array(
                            $redirectAppendField => $responsePayU[$redirectAppendField],
                            $redirectAppendFieldUrl => $responsePayU[$redirectAppendFieldUrl]
                        );
                    return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
                }
                $redirectUrl = $this->container->getParameter('payu.success.route');
                $redirectAppend = $this->container->getParameter('payu.success.order.append');
                $redirectAppendField = $this->container->getParameter('payu.success.order.field');

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('payu.fail.route');
                $redirectAppend = $this->container->getParameter('payu.fail.order.append');
                $redirectAppendField = $this->container->getParameter('payu.fail.order.field');
            }


        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('payu.fail.route');
            $redirectAppend = $this->container->getParameter('payu.fail.order.append');
            $redirectAppendField = $this->container->getParameter('payu.fail.order.field');
        }

        $redirectData   = $redirectAppend
            ? array(
                $redirectAppendField => $this->get('payment.bridge')->getOrderId(),
            )
            : array();
        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }


    /**
     * Payment response
     *
     * @param Request $request Request element
     *
     * @internal param $data
     * @return RedirectResponse
     */
    public function responseAction(Request $request)
    {
        $form = $this->get('form.factory')->create('payu_visanet_view');
        //get parameters
        $visanetUrl = $request->query->get('visanet_url');
        $visanetE = $request->query->get('visanet_e');

        $formView = $this
            ->get('payu_visanet.form.type.wrapper')
            ->buildForm($visanetUrl)
            ->getForm()
            ->createView();
        return array(

            'payu_visanet_form' => $formView,
        );
    }
}