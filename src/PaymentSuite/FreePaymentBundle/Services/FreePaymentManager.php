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

namespace PaymentSuite\FreePaymentBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

/**
 * FreePayment manager.
 */
class FreePaymentManager
{
    /**
     * @var FreePaymentMethodFactory
     *
     * PaymentMethodInterface factory
     */
    private $methodFactory;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    private $paymentEventDispatcher;

    /**
     * Construct method for free payment manager.
     *
     * @param FreePaymentMethodFactory $methodFactory          PaymentMethodInterface factory
     * @param PaymentBridgeInterface   $paymentBridge          Payment Bridge
     * @param PaymentEventDispatcher   $paymentEventDispatcher Event dispatcher
     */
    public function __construct(
        FreePaymentMethodFactory $methodFactory,
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher
    ) {
        $this->methodFactory = $methodFactory;
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
    }

    /**
     * Tries to process a free payment.
     *
     * @return FreePaymentManager Self object
     */
    public function processPayment()
    {
        $paymentMethod = $this
            ->methodFactory
            ->create();

        /**
         * At this point, order must be created given a card, and placed in
         * PaymentBridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $paymentMethod
            );

        /**
         * Order exists right here.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paymentMethod
            );

        /**
         * Payment paid done.
         *
         * Paid process has ended (No matters result)
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $paymentMethod
            );

        /**
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

        return $this;
    }
}
