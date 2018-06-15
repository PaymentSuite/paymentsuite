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

namespace PaymentSuite\StripeBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\StripeBundle\StripeMethod;
use PaymentSuite\StripeBundle\ValueObject\StripeTransaction;

/**
 * Stripe manager.
 */
class StripeManager
{
    /**
     * @var StripeTransactionFactory
     *
     * Transaction factory
     */
    private $transactionFactory;

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
     * Construct method for stripe manager.
     *
     * @param StripeTransactionFactory $transactionFactory     Stripe Transaction factory
     * @param PaymentBridgeInterface   $paymentBridge          Payment Bridge
     * @param PaymentEventDispatcher   $paymentEventDispatcher Event dispatcher
     */
    public function __construct(
        StripeTransactionFactory $transactionFactory,
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher
    ) {
        $this->transactionFactory = $transactionFactory;
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
    }

    /**
     * Tries to process a payment through Stripe.
     *
     * @param StripeMethod $paymentMethod Payment method
     * @param float        $amount        Amount
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentException
     *
     * @return StripeManager Self object
     */
    public function processPayment(StripeMethod $paymentMethod, $amount)
    {
        /**
         * check and set payment data.
         */
        $chargeParams = $this->prepareData(
            $paymentMethod,
            $amount
        );

        /**
         * make payment.
         */
        $transaction = $this
            ->transactionFactory
            ->create($chargeParams);

        /**
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

        /**
         * when a transaction is successful, it is marked as 'closed'.
         */
        if ($transaction['paid'] != 1) {

            /**
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

            throw new PaymentException();
        }

        $paymentMethod
            ->setTransactionId($transaction['id'])
            ->setTransactionStatus('paid')
            ->setTransactionResponse($transaction);

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

    /**
     * Check and set param for payment.
     *
     * @param StripeMethod $paymentMethod Payment method
     * @param float        $amount        Amount
     *
     * @return StripeTransaction Charge params
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     */
    private function prepareData(StripeMethod $paymentMethod, $amount)
    {
        /// first check that amounts are the same
        $cartAmount = intval($this->paymentBridge->getAmount());

        /**
         * If both amounts are different, execute Exception.
         */
        if (abs($amount - $cartAmount) > 0.00001) {
            throw new PaymentAmountsNotMatchException();
        }

        /**
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

        /**
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Order exists right here.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paymentMethod
            );

        return new StripeTransaction(
            $paymentMethod->getApiToken(),
            $cartAmount,
            $this->paymentBridge->getCurrency()
        );
    }
}
