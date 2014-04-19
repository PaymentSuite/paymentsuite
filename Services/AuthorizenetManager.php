<?php

/**
 * AuthorizenetBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package AuthorizenetBundle
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\AuthorizenetBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

use PaymentSuite\AuthorizenetBundle\Services\Wrapper\AuthorizenetTransactionWrapper;
use PaymentSuite\AuthorizenetBundle\AuthorizenetMethod;

/**
 * Authorizenet manager
 */
class AuthorizenetManager
{
    /**
     * @var array
     *
     * Necessary params to request the payment
     */
    protected $chargeParams;

    /**
     * @var string
     *
     * Login ID
     */
    protected $loginId;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;

    /**
     * @var string
     *
     * Transaction key
     */
    protected $tranKey;

    /**
     * @var AuthorizenetTransactionWrapper
     *
     * Transaction wrapper
     */
    protected $transactionWrapper;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment Bridge
     */
    protected $paymentBridge;


    /**
     * Construct method for authorizenet manager
     *
     * @param PaymentEventDispatcher         $paymentEventDispatcher Event dispatcher
     * @param AuthorizenetTransactionWrapper $transactionWrapper     Authorizenet Transaction wrapper
     * @param PaymentBridgeInterface         $paymentBridge          Payment Bridge
     * @param string                         $loginId                Login ID
     * @param string                         $tranKey                Transaction key
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, AuthorizenetTransactionWrapper $transactionWrapper, PaymentBridgeInterface $paymentBridge, $loginId, $tranKey)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->transactionWrapper = $transactionWrapper;
        $this->paymentBridge = $paymentBridge;
        $this->loginId = $loginId;
        $this->tranKey = $tranKey;
    }


    /**
     * Check and set param for payment
     * 
     * @param AuthorizenetMethod $paymentMethod Payment method
     *
     * @return AuthorizenetManager self Object
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     */
    private function prepareData(AuthorizenetMethod $paymentMethod)
    {
        $cartAmount = (float) number_format(($this->paymentBridge->getAmount() / 100), 2, '.', '');

        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
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
         * Order exists right here
         */
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paymentMethod);

        /**
         * Validate the order in the module
         * params for authorizenet interaction
         */
        $extraData = $this->paymentBridge->getExtraData();
        $postValues = array(
            "x_login"			=> $this->loginId,
            "x_tran_key"		=> $this->tranKey,

            "x_version"			=> "3.1",
            "x_delim_data"		=> "TRUE",
            "x_delim_char"		=> "|",
            "x_relay_response"	=> "FALSE",

            "x_type"			=> "AUTH_CAPTURE",
            "x_method"			=> "CC",
            "x_card_num"		=> $paymentMethod->getCreditCartNumber(),
            "x_exp_date"		=> $paymentMethod->getCreditCartExpirationMonth().$paymentMethod->getCreditCartExpirationYear(),

            "x_amount"			=> $cartAmount,
            "x_description"		=> $extraData['order_description'],
        );

        $this->chargeParams = $this->convertPostValues($postValues);

        return $this;
    }

    /**
     * Convert $postValues to the proper format for an http post
     * @param $postValues
     *
     * @return string
     */
    private function convertPostValues($postValues)
    {
        $postString = "";
        foreach( $postValues as $key => $value ) {
            $postString .= "$key=" . urlencode( $value ) . "&";
        }
        $postString = rtrim( $postString, "& " );

        return $postString;
    }


    /**
     * Tries to process a payment through Authorizenet
     *
     * @param AuthorizenetMethod $paymentMethod Payment method
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentException
     *
     * @return AuthorizenetManager Self object
     */
    public function processPayment(AuthorizenetMethod $paymentMethod)
    {
        /**
         * check and set payment data
         */
        $this->prepareData($paymentMethod);
        /**
         * make payment
         */
        $transaction = $this->transactionWrapper->create($this->chargeParams);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        /**
         * when a transaction is successful, it is marked as 'closed'
         */
        if (!isset($transaction[2]) || $transaction[2] != 1) {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);

            throw new PaymentException;
        }

        $paymentMethod
            ->setTransactionId($transaction[37])
            ->setTransactionStatus('paid')
            ->setTransactionResponse($transaction);


        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }
}