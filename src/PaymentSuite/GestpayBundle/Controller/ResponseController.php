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

namespace PaymentSuite\GestpayBundle\Controller;

use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Services\GestpayTransactionIdAssembler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;

/**
 * Class ResponseController.
 *
 * @author WAM Team <develop@wearemarketing.com>
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
     * @var GestpayEncrypter
     */
    private $gestpayEncrypter;
    /**
     * @var GestpayTransactionIdAssembler
     */
    private $transactionIdAssembler;

    /**
     * ResponseController constructor.
     *
     * @param RedirectionRouteCollection    $redirectionRoutes
     * @param UrlGeneratorInterface         $urlGenerator
     * @param GestpayEncrypter              $gestpayEncrypter
     * @param GestpayTransactionIdAssembler $transactionIdAssembler
     */
    public function __construct(
        RedirectionRouteCollection $redirectionRoutes,
        UrlGeneratorInterface $urlGenerator,
        GestpayEncrypter $gestpayEncrypter,
        GestpayTransactionIdAssembler $transactionIdAssembler
    ) {
        $this->redirectionRoutes = $redirectionRoutes;
        $this->urlGenerator = $urlGenerator;
        $this->gestpayEncrypter = $gestpayEncrypter;
        $this->transactionIdAssembler = $transactionIdAssembler;
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
        $orderId = $this->getOrderId($request);

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
        $orderId = $this->getOrderId($request);

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

    /**
     * @param Request $request
     *
     * @return bool|int
     */
    protected function getOrderId(Request $request)
    {
        $encrypted = $request
            ->query
            ->get('b');

        try {
            $decrypted = $this
                ->gestpayEncrypter
                ->decrypt($encrypted);

            $orderId = $this->transactionIdAssembler->extract($decrypted['ShopTransactionID']);
        } catch (\Exception $e) {
            $orderId = false;
        }

        return $orderId;
    }
}
