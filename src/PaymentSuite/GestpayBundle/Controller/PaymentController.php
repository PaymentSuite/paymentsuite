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

use PaymentSuite\GestpayBundle\GestpayMethod;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentLogger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PaymentSuite\GestpayBundle\Services\GestpayManager;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use PaymentSuite\GestpayBundle\Exception\CurrencyNotSupportedException;
use Symfony\Component\HttpFoundation\Response;

/**
 * PaymentController.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaymentController extends Controller
{
    /**
     * @var GestpayManager
     */
    private $gestpayManager;
    /**
     * @var PaymentLogger
     */
    private $logger;

    /**
     * Construct.
     *
     * @param GestpayManager $gestpayManager Payment manager
     */
    public function __construct(
        GestpayManager $gestpayManager,
        PaymentLogger $logger
    ) {
        $this->gestpayManager = $gestpayManager;
        $this->logger = $logger;
    }

    /**
     * @return RedirectResponse
     *
     * @throws PaymentOrderNotFoundException
     * @throws CurrencyNotSupportedException
     */
    public function executeAction()
    {
        /*
         * The execute action will redirect to gestpay gateway.
         */
        $gestpayUrl = $this
            ->gestpayManager
            ->processPayment();

        return new RedirectResponse($gestpayUrl);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function resultAction(Request $request)
    {
        try {
            $this
                ->gestpayManager
                ->processResult($request->query->all());
        } catch (PaymentException $e) {
            $this
                ->logger
                ->log(
                    'error',
                    'Gestpay error "'.$e->getMessage(),
                    GestpayMethod::METHOD_NAME
                );
        }

        return new Response('OK');
    }
}
