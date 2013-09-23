<?php

/**
 * BankwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BankwireBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\BankwireBundle\Services;

use Mmoreram\BankwireBundle\Services\Wrapper\BankwireMethodWrapper;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;

/**
 * Bankwire manager
 */
class BankwireManager
{

    /**
     * @var PaymentEventDispatcher
     * 
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;


    /**
     * @var BankwireTransactionWrapper
     *
     * Bankwire transaction wrapper
     */
    protected $bankwireMethodWrapper;


    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;


    /**
     * Construct method for bankwire manager
     *
     * @param PaymentEventDispatcher    $paymentEventDispatcher    Event dispatcher
     * @param BankwireMethodWrapper $bankwireMethodWrapper Bankwire method wrapper
     * @param PaymentBridgeInterface    $paymentBridge             Payment Bridge
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, BankwireMethodWrapper $bankwireMethodWrapper, PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->bankwireMethodWrapper = $bankwireMethodWrapper;
        $this->paymentBridge = $paymentBridge;
    }


    /**
     * Tries to process a payment through Bankwire
     *
     * @return BankwireManager Self object
     *
     * @throws PaymentOrderNotFoundException
     */
    public function processPayment()
    {
        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         * 
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $this->bankwireMethodWrapper->getBankwireMethod());

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        return $this;
    }


    /**
     * Validates payment, given an Id of an existing order
     * 
     * @param integer $orderId Id from order to validate
     * 
     * @return BankwireManager self Object
     *
     * @throws PaymentOrderNotFoundException
     */
    public function validatePayment($orderId)
    {
        /**
         * Loads order to validate
         */
        $this->paymentBridge->findOrder($orderId);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }
}