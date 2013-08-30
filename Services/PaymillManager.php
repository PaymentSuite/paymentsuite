<?php

/**
 * BeFactory PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymillBundle\Services;

use Befactory\PaymentCoreBundle\Services\Abstracts\AbstractPaymentManager;
use Services_Paymill_Transactions;
use Befactory\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;
use Befactory\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface;
use Befactory\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Befactory\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Befactory\PaymentCoreBundle\Events\PaymentDoneEvent;
use Befactory\PaymentCoreBundle\Events\PaymentSuccessEvent;
use Befactory\PaymentCoreBundle\Events\PaymentFailEvent;
use Befactory\PaymentCoreBundle\PaymentCoreEvents;
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
        $cartAmount = (float) $this->cartWrapper->getAmount() * 100;

        /**
         * If both amounts are diffreent, execute Exception
         */
        if (abs($paymentMethod->getAmount() - $cartAmount) > 0.00001) {

            throw new PaymentAmountsNotMatchException;
        }

        $this->notifyPaymentReady($this->cartWrapper, $this->orderWrapper, $paymentMethod);

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
        $this->notifyPaymentOrderCreated($this->cartWrapper, $this->orderWrapper, $paymentMethod);

        return $this;
    }
}