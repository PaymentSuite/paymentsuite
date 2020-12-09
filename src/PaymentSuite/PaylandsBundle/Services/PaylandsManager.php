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

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Exception\CardInvalidException;
use PaymentSuite\PaylandsBundle\Exception\CardNotFoundException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use WAM\Paylands\ClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PaylandsManager.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsManager
{
    /**
     * @var PaymentBridgeInterface
     *
     * Payment Bridge
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    private $paymentEventDispatcher;

    /**
     * @var PaylandsEventDispatcher
     */
    private $paylandsEventDispatcher;

    /**
     * @var PaylandsApiAdapter
     */
    private $paylandsApiAdapter;

    /**
     * PaylandsManager constructor.
     *
     * @param PaymentBridgeInterface $paymentBridge
     * @param PaymentEventDispatcher $paymentEventDispatcher
     * @param PaylandsEventDispatcher $paylandsEventDispatcher
     * @param PaylandsApiAdapter $paylandsApiAdapter
     */
    public function __construct(
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher,
        PaylandsEventDispatcher $paylandsEventDispatcher,
        PaylandsApiAdapter $paylandsApiAdapter
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paylandsEventDispatcher = $paylandsEventDispatcher;
        $this->paylandsApiAdapter = $paylandsApiAdapter;
    }

    /**
     * Tries to process a payment through Paylands.
     *
     * @param PaylandsMethod $paymentMethod Payment method
     *
     * @return PaylandsManager Self object
     *
     * @throws PaymentException
     * @throws CardInvalidException
     */
    public function processPayment(PaylandsMethod $paymentMethod)
    {
        /*
         * At this point, order must be created given a cart, and placed in PaymentBridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $paymentMethod
            );

        /*
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /*
         * Order exists right here.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paymentMethod
            );

        /*
         * Try to make the payment transaction
         */
        try {
            $this->paylandsApiAdapter->validateCard($paymentMethod);

            $this->paylandsEventDispatcher->notifyCardValid($paymentMethod);

            if (!$paymentMethod->isOnlyTokenizeCard()) {
                $this->paylandsApiAdapter->createTransaction($paymentMethod);
            }

            /*
             * Payment paid done.
             *
             * Paid process has ended ( No matters result )
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderDone(
                    $this->paymentBridge,
                    $paymentMethod
                );

            if (PaylandsMethod::STATUS_OK !== $paymentMethod->getPaymentStatus()) {
                throw new PaymentException(sprintf('Order %s could not be paid',
                    $paymentMethod->getPaymentResult()['order']['uuid'] ?? '-'
                ));
            }

            /*
             * Payment paid successfully.
             *
             * Paid process has ended successfully
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderSuccess(
                    $this->paymentBridge,
                    $paymentMethod
                );
        } catch (PaymentException $e) {
            /*
             * Payment paid failed.
             *
             * Paid process has ended failed
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $paymentMethod
                );

            throw $e;
        }

        return $this;
    }
}
