<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaylandsBundle\Controller;

use PaymentSuite\PaylandsBundle\Exception\CardInvalidException;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaylandsBundle\Services\Interfaces\PaylandsSettingsProviderInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaylandsBundle\Services\PaylandsManager;
use PaymentSuite\PaylandsBundle\Services\PaylandsFormFactory;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PaymentController.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaymentController extends Controller
{
    /**
     * @var PaylandsManager
     *
     * Payment manager
     */
    private $paymentManager;

    /**
     * @var PaylandsFormFactory
     *
     * Method factory
     */
    private $paymentFormFactory;

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
     * @var PaylandsSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * PaymentController constructor.
     *
     * @param PaylandsManager $paymentManager
     * @param PaylandsFormFactory $paymentFormFactory
     * @param RedirectionRouteCollection $redirectionRoutes
     * @param PaymentBridgeInterface $paymentBridge
     * @param UrlGeneratorInterface $urlGenerator
     * @param PaylandsSettingsProviderInterface $settingsProvider
     */
    public function __construct(
        PaylandsManager $paymentManager,
        PaylandsFormFactory $paymentFormFactory,
        RedirectionRouteCollection $redirectionRoutes,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator,
        PaylandsSettingsProviderInterface $settingsProvider
    ) {
        $this->paymentManager = $paymentManager;
        $this->paymentFormFactory = $paymentFormFactory;
        $this->redirectionRoutes = $redirectionRoutes;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
        $this->settingsProvider = $settingsProvider;
    }

    public function executeAction(Request $request)
    {
        /**
         * @var FormInterface
         */
        $form = $this
            ->paymentFormFactory
            ->createEmpty();

        $form->handleRequest($request);

        $redirect = $this
            ->redirectionRoutes
            ->getRedirectionRoute('success');

        try {
            if (!$form->isValid()) {
                throw new PaymentException();
            }

            /** @var PaylandsMethod $paymentMethod */
            $paymentMethod = $form->getData();

            $this
                ->paymentManager
                ->processPayment($paymentMethod);
        }catch (CardInvalidException $e) {

            /**
             * Must redirect to card invalid route.
             */
            $redirect = $this
                ->redirectionRoutes
                ->getRedirectionRoute('card_invalid');

        } catch (\Exception $e) {

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
}
