<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymillBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaymillBundle\Services\Wrapper\PaymillTransactionWrapper;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Mmoreram\PaymillBundle\PaymillMethod;
use Services_Paymill_Transactions;

/**
 * Paymill manager
 */
class PaymillManager
{

    /**
     * @var PaymentEventDispatcher
     * 
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;


    /**
     * @var PaymillTransactionWrapper
     *
     * Paymill transaction wrapper
     */
    protected $paymillTransactionWrapper;


    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;


    /**
     * Construct method for paymill manager
     *
     * @param PaymentEventDispatcher    $paymentEventDispatcher    Event dispatcher
     * @param PaymillTransactionWrapper $paymillTransactionWrapper Paymill Transaction wrapper
     * @param PaymentBridgeInterface    $paymentBridge             Payment Bridge
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymillTransactionWrapper $paymillTransactionWrapper, PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymillTransactionWrapper = $paymillTransactionWrapper;
        $this->paymentBridge = $paymentBridge;
    }


    /**
     * Tries to process a payment through Paymill
     *
     * @param PaymillMethod $paymentMethod Payment method
     * @param float         $amount        Amount
     *
     * @return PaymillManager Self object
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     * @throws PaymentException
     */
    public function processPayment(PaymillMethod $paymentMethod, $amount)
    {
        /// first check that amounts are the same
        $paymentBridgeAmount = (float) $this->paymentBridge->getAmount() * 100;

        /**
         * If both amounts are different, execute Exception
         */
        if (abs($amount - $paymentBridgeAmount) > 0.00001) {

            throw new PaymentAmountsNotMatchException;
        }


        /**
         * At this point, order must be created given a card, and placed in PaymentBridge
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
         * Validate the order in the module
         * params for paymill interaction
         */
        $params = array(
            'amount' => intval($paymentBridgeAmount),
            'currency' => $this->paymentBridge->getCurrency(),
            'token' => $paymentMethod->getApiToken(),
            'description' => $this->paymentBridge->getOrderDescription(),
        );

        $transaction = $this
            ->paymillTransactionWrapper
            ->create($params);

        $this->processTransaction($transaction, $paymentMethod);

        return $this;
    }


    /**
     * Given a paymillTransaction response, as an array, prform desired operations
     * 
     * @param array         $transaction   Transaction
     * @param PaymillMethod $paymentMethod Payment method
     *
     * @return PaymillManager Self object
     * 
     * @throws PaymentException
     */
    private function processTransaction(array $transaction, PaymillMethod $paymentMethod)
    {

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        /**
         * when a transaction is successful, it is marked as 'closed'
         */
        if (empty($transaction['status']) || $transaction['status'] != 'closed') {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);

            throw new PaymentException;
        }


        /**
         * Adding to PaymentMethod transaction information
         * 
         * This information is only available in PaymentOrderSuccess event
         */
        $paymentMethod
            ->setTransactionId($transaction['id'])
            ->setTransactionStatus($transaction['status']);


        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }
}