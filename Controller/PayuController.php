<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;
use PaymentSuite\PayuBundle\Services\PayuManager;

/**
 * PayuController
 */
class PayuController extends Controller
{
    /**
     * Payment notification
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("POST")
     */
    public function notifyAction(Request $request)
    {
        $response = new Response();

        /** @var $logger PaymentLogger */
        $paymentLogger = $this->get('payment.logger');
        $paymentLogger->setPaymentBundle("Payu");
        $paymentLogger->log('Transaction notification received: '.$request->request->all());

        $transactionId = $request->request->get('transaction_id');
        $state = $request->request->get('state_pol');
        if ($transactionId && $state) {
            try {
                /** @var $manager PayuManager */
                $manager = $this->get('payu.manager');
                $manager->processNotification($transactionId, $state);
            }
            catch (\Exception $e) {
                $response->setStatusCode(500);
            }
        } else {
            $response->setStatusCode(500);
        }

        return $response;
    }
}