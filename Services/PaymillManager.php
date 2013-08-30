<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\PaymillBundle\Services;

use Befactory\CorePaymentBundle\Services\Abstracts\AbstractPaymentManager;

use Services_Paymill_Transactions;
use Befactory\CorePaymentBundle\Services\Interfaces\CartWrapperInterface;
use Befactory\CorePaymentBundle\Exception\PaymentAmountsNotMatchException;
use Befactory\CorePaymentBundle\Exception\PaymentException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Befactory\CorePaymentBundle\Events\PaymentDoneEvent;
use Befactory\CorePaymentBundle\Events\PaymentSuccessEvent;
use Befactory\CorePaymentBundle\Events\PaymentFailEvent;
use Befactory\CorePaymentBundle\CorePaymentEvents;
use Befactory\PaymillBundle\PaymillMethod;

/**
 * Paymill manager
 */
class PaymillManager extends AbstractPaymentManager
{

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
    protected $apiEndpoint;


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
     * @param EventDispatcher       $eventDispatcher Event dispatcher
     * @param string                $privateKey      Private key
     * @param string                $publicKey       Public key
     * @param string                $apiEndPoint     Api end point
     * @param OrderWrapperInterface $orderWrapper    Order wrapper
     */
    public function __construct(EventDispatcher $eventDispatcher, $privateKey, $publicKey, $apiEndPoint, CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper)
    {
        parent::__construct($eventDispatcher);

        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->apiEndPoint = $apiEndPoint;
        $this->cartWrapper = $cartWrapper;
        $this->orderWrapper = $orderWrapper;
    }


    /**
     * Tries to process a payment through Paymill
     *
     * @param string $token          The paymill token
     * @param float  $originalAmount The total in euros
     *
     * @return PaymillManager Self object
     *
     * @throws PaymentAmountsNotMatchException When amounts do not match
     * @throws PaymentException Generic payment exception
     */
    public function processPayment(PaymillMethod $paymentMethod)
    {
        /// first check that amounts are the same
        $cartAmount = round($this->cartWrapper->getAmount(), 2) * 100.00;

        if ($paymentMethod->getAmount() != $cartAmount) {

            throw new PaymentAmountsNotMatchException;

            return false;
        }

        $this->notifyPaymentReady($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        //validate the order in the module
        //params for paymill interaction
        $params = array(
            'amount' => intval($cartAmount),
            'currency' => 'EUR',
            'token' => $token,
            'description' => $orderWrapper->getDescription(),
        );

        $transactionsObject = new Services_Paymill_Transactions(
            $this->privateKey,
            $this->apiEndpoint
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
        $this->notifyPaymentDone($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        /**
         * when a transaction is successful, it is marked as 'closed'
         */
        if (empty($transaction['status']) || $transaction['status'] != 'closed') {


            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->notifyPaymentFail($this->cartWrapper, $this->orderWrapper, $paymentMethod);

            throw new PaymentException;
        }


        /**
         * Payment paid succesfuly
         *
         * Paid process has ended succesfuly
         */
        $this->notifyPaymentSuccess($this->cartWrapper, $this->orderWrapper, $paymentMethod);


        /**
         * Notifies Payment
         *
         * This event os thrown when Order is created already
         *
         * At this point, order MUST be created
         */
        $this->notifyPaymentOrderCreated($this->cartWrapper, $this->orderWrapper);

        return $this;
    }
}