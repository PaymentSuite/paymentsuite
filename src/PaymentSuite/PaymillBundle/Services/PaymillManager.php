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

namespace PaymentSuite\PaymillBundle\Services;

use Paymill\Models\Response\Transaction;
use Paymill\Services\PaymillException;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymillBundle\PaymillMethod;
use PaymentSuite\PaymillBundle\Services\Wrapper\PaymillTransactionWrapper;

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
    public function __construct(
        PaymentEventDispatcher $paymentEventDispatcher,
        PaymillTransactionWrapper $paymillTransactionWrapper,
        PaymentBridgeInterface $paymentBridge
    )
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymillTransactionWrapper = $paymillTransactionWrapper;
        $this->paymentBridge = $paymentBridge;
    }

    /**
     * Tries to process a payment through Paymill
     *
     * @param PaymillMethod $paymentMethod Payment method
     * @param integer       $amount        Amount
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
        $paymentBridgeAmount = intval($this->paymentBridge->getAmount());

        /**
         * If both amounts are different, execute Exception
         */
        if ($amount != $paymentBridgeAmount) {

            throw new PaymentAmountsNotMatchException(sprintf(
                    'Amounts differ. Requested: [%s] but in PaymentBridge: [%s].',
                    $amount,
                    $paymentBridgeAmount
                )
            );
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

            throw new PaymentOrderNotFoundException();
        }
        /**
         * Order exists right here
         */
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paymentMethod);

        /**
         * Validate the order in the module
         * params for paymill interaction
         */
        $extraData = $this->paymentBridge->getExtraData();
        $params = array(
            'amount'      => $paymentBridgeAmount,
            'currency'    => $this->paymentBridge->getCurrency(),
            'token'       => $paymentMethod->getApiToken(),
            'description' => $extraData['order_description'],
        );

        try {
            $transaction = $this
                ->paymillTransactionWrapper
                ->create($params['amount'], $params['currency'], $params['token'], $params['description']);

        } catch (PaymillException $e) {
            /**
             * create 'failed' transaction
             */
            $transaction = new Transaction();
            $transaction->setStatus('failed');
            $transaction->setDescription($e->getCode() . ' ' . $e->getMessage());
        }

        $this->processTransaction($transaction, $paymentMethod);

        return $this;
    }

    /**
     * Given a paymillTransaction response, as an array, prform desired operations
     *
     * @param Transaction   $transaction   Transaction
     * @param PaymillMethod $paymentMethod Payment method
     *
     * @return PaymillManager Self object
     *
     * @throws PaymentException
     */
    private function processTransaction(Transaction $transaction, PaymillMethod $paymentMethod)
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
        $transactionStatus = $transaction->getStatus();
        if (empty($transactionStatus) || $transactionStatus != 'closed') {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $paymentMethod->setTransaction($transaction);
            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);

            throw new PaymentException();
        }

        /**
         * Adding to PaymentMethod transaction information
         *
         * This information is only available in PaymentOrderSuccess event
         */
        $paymentMethod
            ->setTransactionId($transaction->getId())
            ->setTransactionStatus($transactionStatus)
            ->setTransaction($transaction);

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }
}
