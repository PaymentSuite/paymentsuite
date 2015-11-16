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

namespace PaymentSuite\BankwireBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

/**
 * Bankwire manager
 */
class BankwireManager
{
    /**
     * @var BankwireMethodFactory
     *
     * Bankwire method factory
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
     * Construct method for bankwire manager
     *
     * @param BankwireMethodFactory  $methodFactory          Bankwire method factory
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     */
    public function __construct(
        BankwireMethodFactory $methodFactory,
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher
    ) {
        $this->methodFactory = $methodFactory;
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
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
        $bankwireMethod = $this
            ->methodFactory
            ->create();

        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $bankwireMethod
            );

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Order exists right here
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $bankwireMethod
            );

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $bankwireMethod
            );

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
        $this
            ->paymentBridge
            ->findOrder($orderId);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $this
                    ->methodFactory
                    ->create()
            );

        return $this;
    }

    /**
     * Decline payment, given an Id of an existing order
     *
     * @param integer $orderId Id from order to decline
     *
     * @return BankwireManager self Object
     *
     * @throws PaymentOrderNotFoundException
     */
    public function declinePayment($orderId)
    {
        /**
         * Loads order to validate
         */
        $this
            ->paymentBridge
            ->findOrder($orderId);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Payment failed
         *
         * Paid process has ended with failure
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderFail(
                $this->paymentBridge,
                $this
                    ->methodFactory
                    ->create()
            );

        return $this;
    }
}
