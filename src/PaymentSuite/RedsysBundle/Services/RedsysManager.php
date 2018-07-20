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

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\RedsysBundle\Exception\InvalidSignatureException;
use PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysOrderTransformerInterface;
use Symfony\Component\Form\FormView;

/**
 * Redsys manager.
 */
class RedsysManager
{
    /**
     * @var RedsysFormTypeBuilder
     *
     * Form Type Builder
     */
    private $redsysFormTypeBuilder;

    /**
     * @var RedsysMethodFactory
     *
     * Redsys method factory
     */
    private $redsysMethodFactory;

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
     * @var RedsysOrderTransformerInterface
     *
     * Redsys order transformer
     */
    private $redsysOrderTransformer;

    /**
     * Construct method for redsys manager.
     *
     * @param RedsysFormTypeBuilder           $redsysFormTypeBuilder  Form Type Builder
     * @param RedsysMethodFactory             $redsysMethodFactory    RedsysMethod factory
     * @param PaymentBridgeInterface          $paymentBridge          Payment Bridge
     * @param PaymentEventDispatcher          $paymentEventDispatcher Event dispatcher
     * @param RedsysOrderTransformerInterface $redsysOrderTransformer
     */
    public function __construct(
        RedsysFormTypeBuilder $redsysFormTypeBuilder,
        RedsysMethodFactory $redsysMethodFactory,
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher,
        RedsysOrderTransformerInterface $redsysOrderTransformer
    ) {
        $this->redsysFormTypeBuilder = $redsysFormTypeBuilder;
        $this->redsysMethodFactory = $redsysMethodFactory;
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->redsysOrderTransformer = $redsysOrderTransformer;
    }

    /**
     * Creates form view for Redsys payment.
     *
     * @return FormView
     *
     * @throws PaymentOrderNotFoundException
     * @throws CurrencyNotSupportedException
     */
    public function processPayment()
    {
        $redsysMethod = $this
            ->redsysMethodFactory
            ->createEmpty();

        /*
         * At this point, order must be created given a cart, and placed in PaymentBridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $redsysMethod
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
                $redsysMethod
            );

        return $this
            ->redsysFormTypeBuilder
            ->buildForm();
    }

    /**
     * Processes the POST request sent by Redsys.
     *
     * @param array $parameters Array with response parameters
     *
     * @return RedsysManager Self object
     *
     * @throws InvalidSignatureException     Invalid signature
     * @throws ParameterNotReceivedException Invalid parameters
     * @throws PaymentException              Payment exception
     */
    public function processResult(array $parameters)
    {
        $redsysMethod = $this
            ->redsysMethodFactory
            ->createFromResultParameters($parameters);

        /*
         * Here PaymentBridge shouldn't have the order loaded, so we find it fromm parameters
         */
        $orderId = $this->redsysOrderTransformer
            ->reverseTransform($redsysMethod->getDsOrder());

        $this->paymentBridge
            ->findOrder($orderId);

        /*
         * Payment paid done.
         *
         * Paid process has ended ( No matters result )
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $redsysMethod
            );

        /*
         * when a transaction is successful, $Ds_Response has a
         * value between 0 and 99.
         */
        if (!$redsysMethod->isTransactionSuccessful()) {
            /*
             * Payment paid failed.
             *
             * Paid process has ended failed
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $redsysMethod
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
                $redsysMethod
            );

        return $this;
    }
}
