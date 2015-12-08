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

namespace PaymentSuite\StripeBundle\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;
use PaymentSuite\StripeBundle\Services\StripeManager;
use PaymentSuite\StripeBundle\Services\StripeMethodFactory;
use PaymentSuite\StripeBundle\StripeMethod;

/**
 * PaymentController.
 */
class PaymentController extends Controller
{
    /**
     * @var StripeManager
     *
     * Payment manager
     */
    private $stripeManager;

    /**
     * @var StripeMethodFactory
     *
     * Method factory
     */
    private $methodFactory;

    /**
     * @var RedirectionRouteCollection
     *
     * Redirection routes
     */
    private $redirectionRoutes;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var UrlGeneratorInterface
     *
     * Url generator
     */
    private $urlGenerator;

    /**
     * @var FormFactory
     *
     * Form factory
     */
    private $formFactory;

    /**
     * Construct.
     *
     * @param StripeManager              $stripeManager     Payment manager
     * @param StripeMethodFactory        $methodFactory     Method factory
     * @param RedirectionRouteCollection $redirectionRoutes Redirection routes
     * @param PaymentBridgeInterface     $paymentBridge     Payment bridge
     * @param UrlGeneratorInterface      $urlGenerator      Url generator
     * @param FormFactory                $formFactory       Form factory
     */
    public function __construct(
        StripeManager $stripeManager,
        StripeMethodFactory $methodFactory,
        RedirectionRouteCollection $redirectionRoutes,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator,
        FormFactory $formFactory
    ) {
        $this->stripeManager = $stripeManager;
        $this->methodFactory = $methodFactory;
        $this->redirectionRoutes = $redirectionRoutes;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
    }

    /**
     * Payment execution.
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
            ->formFactory
            ->create('stripe_view');

        $form->handleRequest($request);
        $redirect = $this
            ->redirectionRoutes
            ->getRedirectionRoute('success');

        try {
            if (!$form->isValid()) {
                throw new PaymentException();
            }

            $data = $form->getData();
            $paymentMethod = $this->createStripeMethod($data);
            $this
                ->stripeManager
                ->processPayment($paymentMethod, $data['amount']);
        } catch (Exception $e) {

            /**
             * Must redirect to fail route.
             */
            $redirect = $this
                ->redirectionRoutes
                ->getRedirectionRoute('failure');
        }

        $redirectUrl = $this
            ->urlGenerator
            ->generate(
                $redirect->getRoute(),
                $redirect->getRouteAttributes(
                    $this->paymentBridge->getOrderId()
                )
            );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * Given some data, creates a StripeMethod object.
     *
     * @param array $data Data
     *
     * @return StripeMethod StripeMethod instance
     */
    private function createStripeMethod(array $data)
    {
        return $this
            ->methodFactory
            ->create(
                $data['api_token'],
                $data['credit_card'],
                '',
                $data['credit_card_expiration_year'],
                $data['credit_card_expiration_month'],
                $data['credit_card_security']
            );
    }
}
