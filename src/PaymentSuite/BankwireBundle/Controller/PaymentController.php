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

namespace PaymentSuite\BankwireBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\BankwireBundle\Services\BankwireManager;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;

/**
 * PaymentController
 */
class PaymentController
{
    /**
     * @var BankwireManager
     *
     * Payment manager
     */
    private $bankwireManager;

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
     * @param BankwireManager        $bankwireManager         Payment manager
     * @param RedirectionRoute       $successRedirectionRoute Success redirection route
     * @param PaymentBridgeInterface $paymentBridge           Payment bridge
     * @param UrlGeneratorInterface  $urlGenerator            Url generator
     */
    public function __construct(
        BankwireManager $bankwireManager,
        RedirectionRoute $successRedirectionRoute,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->bankwireManager = $bankwireManager;
        $this->successRedirectionRoute = $successRedirectionRoute;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Payment execution
     *
     * @return RedirectResponse
     */
    public function executeAction()
    {
        $this
            ->bankwireManager
            ->processPayment();

        $redirectUrl = $this
            ->urlGenerator
            ->generate(
                $this->successRedirectionRoute->getRoute(),
                $this->successRedirectionRoute->getRouteAttributes(
                    $this->paymentBridge->getOrderId()
                )
            );

        return new RedirectResponse($redirectUrl);
    }
}
