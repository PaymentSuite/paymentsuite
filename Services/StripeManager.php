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

namespace dpcat237\StripeBundle\Services;

use Services_Stripe_Transactions;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;

use Mmoreram\PaymentCoreBundle\Services\Wrapper\CurrencyWrapper;
use Mmoreram\PaymentCoreBundle\Event\PaymentDoneEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentSuccessEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentFailEvent;
use Mmoreram\PaymentCoreBundle\PaymentCoreEvents;
use dpcat237\StripeBundle\Services\Wrapper\StripeTransactionWrapper;
use dpcat237\StripeBundle\StripeMethod;
use Stripe;
use Stripe_Charge;

/**
 * Stripe manager
 */
class StripeManager
{
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
     * @var string
     *
     * Stripe api ednpoint
     */
    protected $apiEndPoint;


    /**
     * @var CartWrapperInterface
     *
     * Cart wrapper interface
     */
    protected $cartWrapper;


    /**
     * @var CurrencyWrapper
     *
     * Currency wrapper
     */
    protected $currencyWrapper;


    /**
     * @var OrderWrapperInterface
     *
     * Order wrapper interface
     */
    protected $orderWrapper;


    /**
     * Construct method for stripe manager
     *
     * @param PaymentEventDispatcher   $paymentEventDispatcher Event dispatcher
     * @param StripeTransactionWrapper $transactionWrapper     Transaction wrapper
     * @param string                   $apiEndPoint            Api end point
     * @param CartWrapperInterface     $cartWrapper            Cart wrapper
     * @param CurrencyWrapper          $currencyWrapper        Currency wrapper
     * @param OrderWrapperInterface    $orderWrapper           Order wrapper
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, StripeTransactionWrapper $transactionWrapper, $apiEndPoint, CartWrapperInterface $cartWrapper, CurrencyWrapper $currencyWrapper, OrderWrapperInterface $orderWrapper)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->transactionWrapper = $transactionWrapper;
        $this->apiEndPoint = $apiEndPoint;
        $this->cartWrapper = $cartWrapper;
        $this->currencyWrapper = $currencyWrapper;
        $this->orderWrapper = $orderWrapper;
    }


    /**
     * Tries to process a payment through Stripe
     *
     * @param StripeMethod $paymentMethod
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentException
     *
     * @return StripeManager Self object
     */
    public function processPayment(StripeMethod $paymentMethod)
    {
        /// first check that amounts are the same
        $cartAmount = (float) $this->cartWrapper->getAmount() * 100;

        /**
         * If both amounts are different, execute Exception
         */
        if (abs($paymentMethod->getAmount() - $cartAmount) > 0.00001) {

            throw new PaymentAmountsNotMatchException;
        }

        $this->paymentEventDispatcher->notifyPaymentReady($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        /**
         * Validate the order in the module
         * params for stripe interaction
         */
        $cardParams = array(
            'number' => $paymentMethod->getCreditCartNumber(),
            'exp_month' => $paymentMethod->getCreditCartExpirationMonth(),
            'exp_year' => $paymentMethod->getCreditCartExpirationYear(),
        );
        $chargeParams = array(
            'card' => $cardParams,
            'amount' => intval($cartAmount),
            'currency' => strtolower($this->currencyWrapper->getCurrency()),
        );

        $transaction = $this->transactionWrapper->create($chargeParams);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentDone($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        /**
         * when a transaction is successful, it is marked as 'closed'
         */
        if ($transaction['paid'] != 1) {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentFail($this->cartWrapper, $this->orderWrapper, $paymentMethod);

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
        $this->paymentEventDispatcher->notifyPaymentSuccess($this->cartWrapper, $this->orderWrapper, $paymentMethod);


        /**
         * Notifies Payment
         *
         * This event os thrown when Order is created already
         *
         * At this point, order MUST be created
         */
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        return $this;
    }
}