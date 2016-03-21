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

namespace PaymentSuite\RedsysApiBundle\Controller;

use PaymentSuite\RedsysApiBundle\RedsysApiMethod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\StripeBundle\StripeMethod;

/**
 * RedsysApiController
 */
class RedsysApiController extends Controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @throws PaymentException
     */
    public function executeAction(Request $request)
    {
        /**
         * @var FormInterface $form
         */
        $form = $this
            ->get('form.factory')
            ->create('redsys_api_type');

        $form->handleRequest($request);

        try {
            if (!$form->isValid()) {

                throw new PaymentException();
            }

            $data = $form->getData();
            $paymentMethod = $this->createRedsysApiMethod($data);
            $this
                ->get('redsys_api.manager')
                ->processPayment($paymentMethod, $data['amount']);

            $redirectUrl = $this->container->getParameter('redsys_api.success.route');
            $redirectAppend = $this->container->getParameter('redsys_api.success.order.append');
            $redirectAppendField = $this->container->getParameter('redsys_api.success.order.field');

        } catch (PaymentException $e) {
            /**
             * Must redirect to fail route
             */
            $redirectUrl = $this->container->getParameter('redsys_api.fail.route');
            $redirectAppend = $this->container->getParameter('redsys_api.fail.order.append');
            $redirectAppendField = $this->container->getParameter('redsys_api.fail.order.field');
        }

        $redirectData   = $redirectAppend
                        ? array(
                            $redirectAppendField => $this->get('payment.bridge')->getOrderId()
                        )
                        : array();

        return $this->redirect($this->generateUrl($redirectUrl, $redirectData));
    }

    /**
     * Given some data, creates a StripeMethod object
     *
     * @param array $data Data
     *
     * @return StripeMethod StripeMethod instance
     */
    private function createRedsysApiMethod(array $data)
    {
        $paymentMethod = new RedsysApiMethod();
        $paymentMethod
            ->setCreditCartNumber($data['credit_card'])
            ->setCreditCartExpirationMonth($data['credit_cart_expiration_month'])
            ->setCreditCartExpirationYear($data['credit_cart_expiration_year'])
            ->setCreditCartSecurity($data['credit_cart_security']);

        return $paymentMethod;
    }
}
