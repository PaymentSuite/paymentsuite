<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\GestpayBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\GestpayBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\GestpayBundle\GestpayMethod;
use PaymentSuite\GestpayBundle\Exception\CurrencyNotSupportedException;

/**
 * Gestpay manager.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayManager
{
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
     * @var GestpayEncrypter
     */
    private $gestpayEncrypter;

    /**
     * GestpayManager constructor.
     *
     * @param PaymentBridgeInterface $paymentBridge
     * @param PaymentEventDispatcher $paymentEventDispatcher
     * @param GestpayEncrypter       $gestpayEncrypter
     */
    public function __construct(
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher,
        GestpayEncrypter $gestpayEncrypter
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->gestpayEncrypter = $gestpayEncrypter;
    }

    /**
     * @return string
     *
     * @throws PaymentOrderNotFoundException
     * @throws CurrencyNotSupportedException
     */
    public function processPayment()
    {
        $gestpayMethod = new GestpayMethod();

        /*
         * At this point, order must be created given a cart, and placed in PaymentBridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $gestpayMethod
            );

        /*
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /*
         * Order exists right here.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $gestpayMethod
            );

        return $this->gestpayEncrypter->encryptedUrl();
    }

    /**
     * @param array $parameters
     *
     * @return $this
     *
     * @throws ParameterNotReceivedException
     * @throws PaymentException
     */
    public function processResult(array $parameters)
    {
        if (!isset($parameters['b'])) {
            throw new ParameterNotReceivedException();
        }

        $gestpayMethod = new GestpayMethod();

        $decrypted = $this
            ->gestpayEncrypter
            ->decrypt($parameters['b']);

        /*
         * Adding transaction information to PaymentMethod.
         *
         * This information is only available in PaymentOrderSuccess event
         */
        $gestpayMethod
            ->setTransactionResult($decrypted['TransactionResult'])
            ->setShopTransactionId($decrypted['ShopTransactionID'])
            ->setBankTransactionId($decrypted['BankTransactionID'])
            ->setAuthorizationCode($decrypted['AuthorizationCode'])
            ->setCurrency($decrypted['Currency'])
            ->setAmount($decrypted['Amount'])
            ->setErrorCode($decrypted['ErrorCode'])
            ->setErrorDescription($decrypted['ErrorDescription']);

        $this->loadPaymentBridgeOrder($gestpayMethod);

        /*
         * Payment paid done.
         *
         * Paid process has ended ( No matters result )
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $gestpayMethod
            );

        if (!$this->isValidTransaction($gestpayMethod)) {
            /*
             * Payment paid failed.
             *
             * Paid process has ended failed
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $gestpayMethod
                );

            throw new PaymentException();
        }

        /*
         * Payment paid successfully.
         *
         * Paid process has ended successfully
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $gestpayMethod
            );
    }

    /**
     * @param GestpayMethod $gestpayMethod
     *
     * @throws PaymentOrderNotFoundException
     */
    protected function loadPaymentBridgeOrder(GestpayMethod $gestpayMethod)
    {
        $orderId = GestpayOrderIdAssembler::extract($gestpayMethod->getShopTransactionId());

        /**
         * Retrieving the order object.
         */
        $order = $this
            ->paymentBridge
            ->findOrder($orderId);

        if (!$order) {
            throw new PaymentOrderNotFoundException();
        }
    }

    /**
     * @param GestpayMethod $gestpayMethod
     *
     * @return bool
     */
    private function isValidTransaction(GestpayMethod $gestpayMethod)
    {
        if ('OK' != $gestpayMethod->getTransactionResult()) {
            return false;
        }

        if ($gestpayMethod->getAmount() * 100 != $this->paymentBridge->getAmount()) {
            return false;
        }

        return true;
    }
}
