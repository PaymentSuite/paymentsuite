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

namespace PaymentSuite\RedsysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

use PaymentSuite\RedsysBundle\Services\RedsysManager;

/**
 * PaymentController
 */
class PaymentController extends Controller
{
    /**
     * @var RedsysManager
     *
     * Payment manager
     */
    private $redsysManager;

    /**
     * @var EngineInterface
     *
     * Template engine
     */
    private $templatingEngine;

    /**
     * Construct
     *
     * @param RedsysManager   $redsysManager    Payment manager
     * @param EngineInterface $templatingEngine Payment bridge
     */
    public function __construct(
        RedsysManager $redsysManager,
        EngineInterface $templatingEngine
    ) {
        $this->redsysManager = $redsysManager;
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * Payment execution
     *
     * @return Response
     */
    public function executeAction()
    {
        /**
         * The execute action will generate the Paypal web
         * checkout form before redirecting
         */
        $formView = $this
            ->redsysManager
            ->processPayment();

        return $this
            ->templatingEngine
            ->render('PaypalWebCheckoutBundle:Paypal:process.html.twig', [
                'paypal_form' => $formView,
            ]);
    }
}
