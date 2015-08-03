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

use PaymentSuite\BankwireBundle\Services\Wrapper\BankwireMethodWrapper;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

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
     * @var BankwireMethodWrapper
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
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param BankwireMethodWrapper  $bankwireMethodWrapper  Bankwire method wrapper
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     */
    public function __construct(
        PaymentEventDispatcher $paymentEventDispatcher,
        BankwireMethodWrapper $bankwireMethodWrapper,
        PaymentBridgeInterface $paymentBridge
    )
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
        $bankwireMethod = $this
            ->bankwireMethodWrapper
            ->getBankwireMethod();

        /**
         * At this point, order must be created given a card, and placed in PaymentBridge
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
        $this->paymentBridge->findOrder($orderId);

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
                    ->bankwireMethodWrapper
                    ->getBankwireMethod()
            );

        return $this;
    }
}
