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

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysBundle\Exception\InvalidSignatureException;
use PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\RedsysBundle\Exception\PaymentResponseException;
use PaymentSuite\RedsysBundle\RedsysMethod;
use PaymentSuite\RedsysBundle\Services\Wrapper\RedsysFormTypeWrapper;

/**
 * Redsys manager
 */
class RedsysManager
{
    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;

    /**
     * @var Wrapper\RedsysFormTypeWrapper
     *
     * Form Type Wrapper
     */
    protected $redsysFormTypeWrapper;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;

    /**
     * @var string
     *
     * Secret key
     */
    protected $secretKey;

    /**
     * @var RedsysSignature
     *
     * Generate hash sha-256
     */
    protected $redsysSignature;

    /**
     * Construct method for redsys manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param RedsysFormTypeWrapper  $redsysFormTypeWrapper  Redsys form typ wrapper
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param RedsysSignature        $redsysSignature        Redsys Signature
     * @param string                 $secretKey              Secret Key
     */
    public function __construct(
        PaymentEventDispatcher $paymentEventDispatcher,
        RedsysFormTypeWrapper $redsysFormTypeWrapper,
        PaymentBridgeInterface $paymentBridge,
        RedsysSignature $redsysSignature,
        $secretKey
    )
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->redsysFormTypeWrapper = $redsysFormTypeWrapper;
        $this->paymentBridge = $paymentBridge;
        $this->redsysSignature = $redsysSignature;
        $this->secretKey = $secretKey;

    }

    /**
     * Creates form view for Redsys payment
     *
     * @return \Symfony\Component\Form\FormView
     *
     * @throws PaymentOrderNotFoundException
     */
    public function processPayment()
    {
        $redsysMethod = new RedsysMethod();
        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $redsysMethod
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
                $redsysMethod
            );

        $formView = $this
            ->redsysFormTypeWrapper
            ->buildForm();

        return $formView;
    }

    /**
     * Processes the POST request sent by Redsys
     *
     * @param array $parameters Array with response parameters
     *
     * @return RedsysManager Self object
     *
     * @throws InvalidSignatureException
     * @throws ParameterNotReceivedException
     * @throws PaymentException
     */
    public function processResult(array $response)
    {
        //Check we receive all needed parameters
        $Ds_Signature = $response['Ds_Signature'];

        $parameters = (array) json_decode(base64_decode($response['Ds_MerchantParameters']));
        $parameters = array_change_key_case($parameters, CASE_UPPER);

        $this->checkResultParameters($parameters);

        $redsysMethod =  new RedsysMethod();

        $dsSignature           = $Ds_Signature;
        $dsResponse            = $parameters['DS_RESPONSE'];
        $dsAmount              = $parameters['DS_AMOUNT'];
        $dsOrder               = $parameters['DS_ORDER'];
        $dsMerchantCode        = $parameters['DS_MERCHANTCODE'];
        $dsCurrency            = $parameters['DS_CURRENCY'];
        $dsSecret               = $this->secretKey;
        $dsDate                 = $parameters['DS_DATE'];
        $dsHour                 = $parameters['DS_HOUR'];
        $dsSecurePayment        = $parameters['DS_SECUREPAYMENT'];
        $dsCardCountry          = $parameters['DS_CARD_COUNTRY'];
        $dsAuthorisationCode    = $parameters['DS_AUTHORISATIONCODE'];
        $dsConsumerLanguage     = $parameters['DS_CONSUMERLANGUAGE'];
        $dsCardType             = (array_key_exists('DS_CARD_TYPE', $parameters) ? $parameters['DS_CARD_TYPE'] : '');
        $dsMerchantData         = (array_key_exists('DS_MERCHANTDATA', $parameters) ? $parameters['DS_MERCHANTDATA'] : '');

        $internalSignature = $this
            ->redsysSignature
            ->sign(
                $dsOrder,
                $dsSecret,
                $response['Ds_MerchantParameters']
            );

        /**
         * Validate if signature from Redsys and our signature are identical,
         */
        $this->redsysSignature->checkSign($dsSignature, $internalSignature);

        /**
         * Adding transaction information to PaymentMethod
         *
         * This information is only available in PaymentOrderSuccess event
         */
        $redsysMethod
            ->setDsResponse($dsResponse)
            ->setDsAuthorisationCode($dsAuthorisationCode)
            ->setDsCardCountry($dsCardCountry)
            ->setDsCardType($dsCardType)
            ->setDsConsumerLanguage($dsConsumerLanguage)
            ->setDsDate($dsDate)
            ->setDsHour($dsHour)
            ->setDsSecurePayment($dsSecurePayment)
            ->setDsOrder($dsOrder);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $redsysMethod
            );

        /**
         * when a transaction is successful, $Ds_Response has a value between 0 and 99
         */
        $this->transactionSuccessful($dsResponse, $redsysMethod);

        /**
         * Payment paid successfully
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

    /**
     * Returns true if the transaction was successful
     *
     * @param string $dsResponse Response code
     *
     * @return boolean
     */
    protected function transactionSuccessful($dsResponse, $redsysMethod)
    {
        /**
         * When a transaction is successful, $Ds_Response has a value between 0 and 99
         */
        if (intval($dsResponse)>=100 ) {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $redsysMethod
                );

            throw new PaymentResponseException(false, $dsResponse);
        }
    }

    /**
     * Checks that all the required parameters are received
     *
     * @param array $parameters Parameters
     *
     * @throws \PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException
     */
    protected function checkResultParameters(array $parameters)
    {

        $list = array(
            'DS_DATE',
            'DS_HOUR',
            'DS_AMOUNT',
            'DS_CURRENCY',
            'DS_ORDER',
            'DS_MERCHANTCODE',
            'DS_TERMINAL',
            'DS_RESPONSE',
            'DS_TRANSACTIONTYPE',
            'DS_SECUREPAYMENT',
            'DS_CARD_COUNTRY',
            'DS_AUTHORISATIONCODE',
            'DS_CONSUMERLANGUAGE',
        );
        foreach ($list as $item) {
            if (!isset($parameters[$item])) {
                throw new ParameterNotReceivedException($item);
            }
        }
    }
}
