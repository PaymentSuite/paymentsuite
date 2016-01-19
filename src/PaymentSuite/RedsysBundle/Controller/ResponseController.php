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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;

/**
 * Class ResponseController.
 */
class ResponseController
{
    /**
     * @var RedirectionRouteCollection
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
     * Construct.
     *
     * @param RedirectionRouteCollection $redirectionRoutes Redirection routes
     * @param UrlGeneratorInterface      $urlGenerator      Url generator
     */
    public function __construct(
        RedirectionRouteCollection $redirectionRoutes,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->redirectionRoutes = $redirectionRoutes;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Payment success action.
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function successAction(Request $request)
    {
        $orderId = $request
            ->query
            ->get('order_id', false);

        $successRoute = $this
            ->redirectionRoutes
            ->getRedirectionRoute('success');

        $redirectUrl = $this
            ->urlGenerator
            ->generate(
                $successRoute->getRoute(),
                $successRoute->getRouteAttributes(
                    $orderId
                )
            );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * Payment fail action.
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function failureAction(Request $request)
    {
        $orderId = $request
            ->query
            ->get('order_id', false);

        $failureRoute = $this
            ->redirectionRoutes
            ->getRedirectionRoute('failure');

        $redirectUrl = $this
            ->urlGenerator
            ->generate(
                $failureRoute->getRoute(),
                $failureRoute->getRouteAttributes(
                    $orderId
                )
            );

        return new RedirectResponse($redirectUrl);
    }
}
