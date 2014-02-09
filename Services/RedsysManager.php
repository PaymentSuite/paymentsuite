<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2014
 */

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Services\Wrapper\RedsysFormTypeWrapper;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysBundle\RedsysMethod;
use PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\RedsysBundle\Exception\InvalidSignatureException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

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
     * @var SecretKey
     */
    protected $secretKey;



    /**
     * Construct method for redsys manager
     *
     * @param PaymentEventDispatcher    $paymentEventDispatcher    Event dispatcher
     * @param RedsysFormTypeWrapper $redsysFormTypeWrapper Redsys form typ wrapper
     * @param PaymentBridgeInterface    $paymentBridge             Payment Bridge
     * @param $secretKey Secret Key
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher,
                                RedsysFormTypeWrapper $redsysFormTypeWrapper,
                                PaymentBridgeInterface $paymentBridge,
                                $secretKey)
    {
        $this->paymentEventDispatcher   = $paymentEventDispatcher;
        $this->redsysFormTypeWrapper    = $redsysFormTypeWrapper;
        $this->paymentBridge            = $paymentBridge;
        $this->secretKey                = $secretKey;

    }


    /**
     * Tries to process a payment through Redsys
     *
     * @return RedsysManager Self object
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
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $redsysMethod);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        /**
         * Order exists right here
         */
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $redsysMethod);


        $formView = $this->redsysFormTypeWrapper->buildForm();

        return $formView;
    }

    /**
     * Tries to process a payment through Redsys
     *
     * @param $parameters Array with response parameters
     *
     * @return RedsysManager Self object
     *
     * @throws InvalidSignatureException
     * @throws ParameterNotReceivedException
     */
    public function processResult(array $parameters)
    {
        //Check we receive all needed parameters
        $this->checkResultParameters($parameters);

        $redsysMethod =  new RedsysMethod();

        $dsSignature           = $parameters['Ds_Signature'];
        $dsResponse            = $parameters['Ds_Response'];
        $dsAmount              = $parameters['Ds_Amount'];
        $dsOrder               = $parameters['Ds_Order'];
        $dsMerchantCode        = $parameters['Ds_MerchantCode'];
        $dsCurrency            = $parameters['Ds_Currency'];
        $dsSecret               = $this->secretKey;
        $dsDate                 = $parameters['Ds_Date'];
        $dsHour                 = $parameters['Ds_Hour'];
        $dsSecurePayment        = $parameters['Ds_SecurePayment'];
        $dsCardCountry          = $parameters['Ds_Card_Country'];
        $dsAuthorisationCode    = $parameters['Ds_AuthorisationCode'];
        $dsConsumerLanguage     = $parameters['Ds_ConsumerLanguage'];
        $dsCardType             = $parameters['Ds_Card_Type'];

        if ($dsSignature != $this->expectedSignature($dsAmount, $dsOrder, $dsMerchantCode, $dsCurrency, $dsResponse,  $dsSecret)){
            throw new InvalidSignatureException();
        }

        /**
         * Adding to PaymentMethod transaction information
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
            ->setDsSecurePayment($dsSecurePayment);


        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $redsysMethod);

        /**
         * when a transaction is successful, $Ds_Response has a value between 0 and 99
         */
        if (intval($dsResponse)<0 || intval($dsResponse)>99 ) {
            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $redsysMethod);

            throw new PaymentException;
        }

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $redsysMethod);

        return $this;
    }



    protected function expectedSignature($amount, $order, $merchantCode, $currency, $response, $secret){

        $signature = $amount . $order . $merchantCode . $currency . $response . $secret;
        // SHA1
        return strtoupper(sha1($signature));

    }

    protected function checkResultParameters(array $parameters){
        $list = array(
                    'Ds_Date',
                    'Ds_Hour',
                    'Ds_Signature',
                    'Ds_Amount',
                    'Ds_Currency',
                    'Ds_Order',
                    'Ds_MerchantCode',
                    'Ds_Terminal',
                    'Ds_Signature',
                    'Ds_Response',
                    'Ds_TransactionType',
                    'Ds_SecurePayment',
                    'Ds_MerchantData',
                    'Ds_Card_Country',
                    'Ds_AuthorisationCode',
                    'Ds_ConsumerLanguage',
                    'Ds_Card_Type'
        );
        foreach ($list as $item){
            if(!isset($parameters[$item])){
               throw new ParameterNotReceivedException($item);
            }
        }


    }
}