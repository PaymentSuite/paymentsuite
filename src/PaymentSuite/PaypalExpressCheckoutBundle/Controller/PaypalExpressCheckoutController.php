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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaypalExpressCheckoutBundle\Services\PaypalExpressCheckoutManager;
use PaymentSuite\PaypalExpressCheckoutBundle\Services\PaypalExpressCheckoutMethodFactory;

/**
 * PaypalExpressCheckoutController
 */
class PaypalExpressCheckoutController extends Controller
{
    /**
     * @var PaypalExpressCheckoutManager
     *
     * Payment manager
     */
    private $paypalExpressCheckoutManager;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var CompleteRedirectionRoute
     *
     * Redirection routes
     */
    private $redirectionRoutes;

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
     * @var PaypalExpressCheckoutMethodFactory
     *
     * Method factory
     */
    private $paypalExpressCheckoutMethodFactory;

    /**
     * Construct
     *
     * @param PaypalExpressCheckoutManager       $paypalExpressCheckoutManager       Payment manager
     * @param PaymentBridgeInterface             $paymentBridge                      Payment bridge
     * @param CompleteRedirectionRoute           $redirectionRoutes                  Redirection routes
     * @param UrlGeneratorInterface              $urlGenerator                       Url generator
     * @param FormFactory                        $formFactory                        Form factory
     * @param PaypalExpressCheckoutMethodFactory $paypalExpressCheckoutMethodFactory Method factory
     */
    public function __construct(
        PaypalExpressCheckoutManager $paypalExpressCheckoutManager,
        PaymentBridgeInterface $paymentBridge,
        CompleteRedirectionRoute $redirectionRoutes,
        UrlGeneratorInterface $urlGenerator,
        FormFactory $formFactory,
        PaypalExpressCheckoutMethodFactory $paypalExpressCheckoutMethodFactory
    ) {
        $this->paypalExpressCheckoutManager = $paypalExpressCheckoutManager;
        $this->paymentBridge = $paymentBridge;
        $this->redirectionRoutes = $redirectionRoutes;
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->paypalExpressCheckoutMethodFactory = $paypalExpressCheckoutMethodFactory;
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
        $form = $this
            ->formFactory
            ->create('paypal_express_checkout_view');
        $form->handleRequest($request);

        $redirectRoute = $this
            ->redirectionRoutes
            ->getSuccessRedirectRoute();

        try {
            $data = $form->getData();

            $paymentMethod = $this
                ->paypalExpressCheckoutMethodFactory
                ->create(
                    $data['amount'],
                    $data['currency'],
                    $data['paypal_express_params']
                );
            $this
                ->get('paypal_express_checkout.manager')
                ->preparePayment($paymentMethod);
        } catch (PaymentException $e) {

            /**
             * Must redirect to fail route
             */
            $redirectRoute = $this
                ->redirectionRoutes
                ->getFailureRedirectRoute();
        }

        $redirectUrl = $this
            ->urlGenerator
            ->generate(
                $redirectRoute->getRoute(),
                $redirectRoute->getRouteAttributes(
                    $this->paymentBridge->getOrderId()
                )
            );

        return new RedirectResponse($redirectUrl);
    }
}
