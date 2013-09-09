<?php

/**
 * BeFactory PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Mmoreram 2013
 */

namespace Mmoreram\PaymillBundle\Services;

use Services_Paymill_Transactions;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;

use Mmoreram\PaymentCoreBundle\Event\PaymentDoneEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentSuccessEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentFailEvent;
use Mmoreram\PaymentCoreBundle\PaymentCoreEvents;
use Mmoreram\PaymillBundle\PaymillMethod;

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
     * @var string
     *
     * Paymill public key
     */
    protected $publicKey;


    /**
     * @var string
     *
     * Paymill private key
     */
    protected $privateKey;


    /**
     * @var string
     *
     * Paymill api ednpoint
     */
    protected $apiEndPoint;


    /**
     * @var CartWrapperInterface
     *
     * Cart wrapper interface
     */
    protected $cartWrapper;


    /**
     * @var OrderWrapperInterface
     *
     * Order wrapper interface
     */
    protected $orderWrapper;


    /**
     * Construct method for paymill manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param string                 $privateKey             Private key
     * @param string                 $publicKey              Public key
     * @param string                 $apiEndPoint            Api end point
     * @param CartWrapperInterface   $cartWrapper            Cart wrapper
     * @param OrderWrapperInterface  $orderWrapper           Order wrapper
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, $privateKey, $publicKey, $apiEndPoint, CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->apiEndPoint = $apiEndPoint;
        $this->cartWrapper = $cartWrapper;
        $this->orderWrapper = $orderWrapper;
    }


    /**
     * Tries to process a payment through Paymill
     *
     * @param PaymillMethod $paymentMethod
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentException
     *
     * @return PaymillManager Self object
     */
    public function processPayment(PaymillMethod $paymentMethod)
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
         * params for paymill interaction
         */
        $params = array(
            'amount' => intval($cartAmount),
            'currency' => 'EUR',
            'token' => $paymentMethod->getApiToken(),
            'description' => $this->cartWrapper->getCartDescription(),
        );

        $transactionsObject = new Services_Paymill_Transactions(
            $this->privateKey,
            $this->apiEndPoint
        );
        $transaction = $transactionsObject->create($params);

        $paymentMethod
            ->setTransactionId($transaction['id'])
            ->setTransactionStatus($transaction['status']);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentDone($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        /**
         * when a transaction is successful, it is marked as 'closed'
         */
        if (empty($transaction['status']) || $transaction['status'] != 'closed') {


            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentFail($this->cartWrapper, $this->orderWrapper, $paymentMethod);

            throw new PaymentException;
        }


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