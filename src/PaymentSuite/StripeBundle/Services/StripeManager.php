<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package StripeBundle
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\StripeBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\StripeBundle\Services\Wrapper\StripeTransactionWrapper;
use PaymentSuite\StripeBundle\StripeMethod;

/**
 * Stripe manager
 */
class StripeManager
{

    /**
     * @var array
     *
     * Necessary params to request the payment
     */
    protected $chargeParams;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;

    /**
     * @var StripeTransactionWrapper
     *
     * Transaction wrapper
     */
    protected $transactionWrapper;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment Bridge
     */
    protected $paymentBridge;

    /**
     * Construct method for stripe manager
     *
     * @param PaymentEventDispatcher   $paymentEventDispatcher Event dispatcher
     * @param StripeTransactionWrapper $transactionWrapper     Stripe Transaction wrapper
     * @param PaymentBridgeInterface   $paymentBridge          Payment Bridge
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, StripeTransactionWrapper $transactionWrapper, PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->transactionWrapper = $transactionWrapper;
        $this->paymentBridge = $paymentBridge;
    }

    /**
     * Check and set param for payment
     *
     * @param StripeMethod $paymentMethod Payment method
     * @param float        $amount        Amount
     *
     * @return StripeManager self Object
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     */
    private function prepareData(StripeMethod $paymentMethod, $amount)
    {
        /// first check that amounts are the same
        $cartAmount = (float) $this->paymentBridge->getAmount();

        /**
         * If both amounts are different, execute Exception
         */
        if (abs($amount - $cartAmount) > 0.00001) {
            throw new PaymentAmountsNotMatchException;
        }

        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException;
        }

        /**
         * Order exists right here
         */
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paymentMethod);

        /**
         * Validate the order in the module
         * params for stripe interaction
         */
        $cardParams = array(
            'number' => $paymentMethod->getCreditCartNumber(),
            'exp_month' => $paymentMethod->getCreditCartExpirationMonth(),
            'exp_year' => $paymentMethod->getCreditCartExpirationYear(),
        );

        $this->chargeParams = array(
            'card' => $cardParams,
            'amount' => intval($cartAmount),
            'currency' => strtolower($this->paymentBridge->getCurrency()),
        );

        return $this;
    }

    /**
     * Tries to process a payment through Stripe
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
         * check and set payment data
         */
        $this->prepareData($paymentMethod, $amount);
        /**
         * make payment
         */
        $transaction = $this->transactionWrapper->create($this->chargeParams);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        /**
         * when a transaction is successful, it is marked as 'closed'
         */
        if ($transaction['paid'] != 1) {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);

            throw new PaymentException;
        }

        $paymentMethod
            ->setTransactionId($transaction['id'])
            ->setTransactionStatus('paid')
            ->setTransactionResponse($transaction);

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }
}
