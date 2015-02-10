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

namespace PaymentSuite\PayUBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            } catch (\Exception $e) {
                $response->setStatusCode(500);
            }
        } else {
            $response->setStatusCode(500);
        }

        return $response;
    }
}
