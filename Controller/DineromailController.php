<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author David Pujadas <dpujadas@gmail.com>
 * @package DineromailBundle
 *
 * David Pujadas 2013
 */

namespace Dpujadas\DineromailBundle\Controller;

use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dpujadas\DineromailBundle\DineromailMethod;


/**
 * DineromailController
 */
class DineromailController extends Controller
{

    public function processAction(Request $request)
    {
        $paymentMethod = new DineromailMethod();
        /** @var PaymentBridgeInterface $paymentBridge  */
        $paymentBridge = $this->get('payment.bridge');

        /** @var PaymentEventDispatcher $eventDispatcher  */
        $eventDispatcher = $this->get('payment.event.dispatcher');
        $eventDispatcher->notifyPaymentOrderLoaded($paymentBridge, $paymentMethod);

    }

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->createNamedBuilder(null, 'form', $defaultData)
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->cartWrapper->getAmount(), 2) * 100
            ))
            ->add('payment_processer', 'hidden', array(
                'data'  =>  'paymill_processer'
            ))
            ->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            $paymentMethod = new PaymillMethod;
            $paymentMethod
                ->setAmount((float) $data['amount'])
                ->setApiToken($data['api_token'])
                ->setCreditCartNumber($data['credit_cart_1'] . $data['credit_cart_2'] . $data['credit_cart_3'] . $data['credit_cart_4'])
                ->setCreditCartOwner($data['credit_cart_owner'])
                ->setCreditCartExpirationMonth($data['credit_cart_expiration_month'])
                ->setCreditCartExpirationYear($data['credit_cart_expiration_year'])
                ->setCreditCartSecurity($data['credit_cart_security']);

            try {
                $this
                    ->get('paymill.manager')
                    ->processPayment($paymentMethod);

                $redirectUrl = $this->container->getParameter('paymill.success.route');
                $redirectAppend = $this->container->getParameter('paymill.success.order.append');
                $redirectAppendField = $this->container->getParameter('paymill.success.order.field');
                $redirectAppendValue = $this->get('payment.order.wrapper')->getOrderId();

            } catch (PaymentException $e) {

                /**
                 * Must redirect to fail route
                 */
                $redirectUrl = $this->container->getParameter('paymill.fail.route');
                $redirectAppend = $this->container->getParameter('paymill.fail.cart.append');
                $redirectAppendField = $this->container->getParameter('paymill.fail.cart.field');
                $redirectAppendValue = $this->get('payment.cart.wrapper')->getCartId();

                throw $e;
            }
        } else {

            /**
             * If form is not valid, fail return page
             */
            $redirectUrl = $this->container->getParameter('paymill.fail.route');
            $redirectAppend = $this->container->getParameter('paymill.fail.cart.append');
            $redirectAppendField = $this->container->getParameter('paymill.fail.cart.field');
            $redirectAppendValue = $this->get('payment.cart.wrapper')->getCartId();
        }

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $redirectAppendValue,
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }
}