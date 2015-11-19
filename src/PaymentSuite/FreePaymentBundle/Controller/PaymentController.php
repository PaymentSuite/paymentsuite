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

namespace PaymentSuite\FreePaymentBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\FreePaymentBundle\Services\FreePaymentManager;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;

/**
 * PaymentController
 */
class PaymentController
{
    /**
     * @var FreePaymentManager
     *
     * Payment manager
     */
    private $freePaymentManager;

    /**
     * @var RedirectionRoute
     *
     * Redirection route for success
     */
    private $successRedirectionRoute;

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
     * Construct
     *
     * @param FreePaymentManager     $freePaymentManager      Payment manager
     * @param RedirectionRoute       $successRedirectionRoute Success redirection route
     * @param PaymentBridgeInterface $paymentBridge           Payment bridge
     * @param UrlGeneratorInterface  $urlGenerator            Url generator
     */
    public function __construct(
        FreePaymentManager $freePaymentManager,
        RedirectionRoute $successRedirectionRoute,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->freePaymentManager = $freePaymentManager;
        $this->successRedirectionRoute = $successRedirectionRoute;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Free Payment execution
     *
     * @return RedirectResponse
     */
    public function executeAction()
    {
        $this
            ->freePaymentManager
            ->processPayment();

        $successUrl = $this
            ->urlGenerator
            ->generate(
                $this->successRedirectionRoute->getRoute(),
                $this->successRedirectionRoute->getRouteAttributes(
                    $this->paymentBridge->getOrderId()
                )
            );

        return new RedirectResponse($successUrl);
    }
}
