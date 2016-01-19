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

namespace PaymentSuite\RedsysBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;
use PaymentSuite\RedsysBundle\Services\RedsysManager;

/**
 * Class ResultController.
 */
class ResultController
{
    /**
     * @var RedsysManager
     *
     * Payment manager
     */
    private $redsysManager;

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
     * Construct.
     *
     * @param RedsysManager              $redsysManager     Payment manager
     * @param RedirectionRouteCollection $redirectionRoutes Redirection routes
     * @param PaymentBridgeInterface     $paymentBridge     Payment bridge
     * @param UrlGeneratorInterface      $urlGenerator      Url generator
     */
    public function __construct(
        RedsysManager $redsysManager,
        RedirectionRouteCollection $redirectionRoutes,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->redsysManager = $redsysManager;
        $this->redirectionRoutes = $redirectionRoutes;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Payment execution.
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     */
    public function resultAction(Request $request)
    {
        $redirectRoute = $this
            ->redirectionRoutes
            ->getRedirectionRoute('success');

        try {
            $this
                ->redsysManager
                ->processResult($request
                    ->request
                    ->all()
                );
        } catch (PaymentException $e) {

            /**
             * Must redirect to fail route.
             */
            $redirectRoute = $this
                ->redirectionRoutes
                ->getRedirectionRoute('failure');
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
