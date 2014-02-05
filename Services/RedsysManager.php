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

use PaymentSuite\RedsysBundle\Services\Wrapper\RedsysMethodWrapper;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
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
     * @var RedsysTransactionWrapper
     *
     * Redsys transaction wrapper
     */
    protected $redsysMethodWrapper;


    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;

    /**
     * Construct method for redsys manager
     *
     * @param PaymentEventDispatcher    $paymentEventDispatcher    Event dispatcher
     * @param RedsysMethodWrapper $redsysMethodWrapper Redsys method wrapper
     * @param PaymentBridgeInterface    $paymentBridge             Payment Bridge
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, RedsysMethodWrapper $redsysMethodWrapper, PaymentBridgeInterface $paymentBridge,TimedTwigEngine $templating)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->redsysMethodWrapper = $redsysMethodWrapper;
        $this->paymentBridge = $paymentBridge;
        $this->templating = $templating;
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
        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $this->redsysMethodWrapper->getRedsysMethod());

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        /**
         * Order exists right here
         */
        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $this->redsysMethodWrapper->getRedsysMethod());

        $amount          = (integer) ($this->paymentBridge->getAmount() * 100);
        $orderNumber     = $this->formatOrderNumber($this->paymentBridge->getOrderNumber());
        $merchantCode    = $this->redsysMethodWrapper->getMerchantCode();
        $currency        = $this->currencyTranslation($this->paymentBridge->getCurrency());


        $extraData = $this->paymentBridge->getExtraData();


        $transactionType = $extraData['transaction_type'];
        $terminal        = $extraData['terminal'];
        $merchantURL     = 'http://redsys.dev/redsys/transaction';
        $secret          = $this->redsysMethodWrapper->getSecretKey();

        $opts = array();
        $opts['Ds_Merchant_Amount'] = $amount;
        $opts['Ds_Merchant_MerchantSignature'] = $this->shopSignature($amount, $orderNumber, $merchantCode, $currency, $transactionType, $merchantURL, $secret);
        $opts['Ds_Merchant_MerchantCode'] = $merchantCode;
        $opts['Ds_Merchant_Currency'] = $currency;
        $opts['Ds_Merchant_Terminal'] = $terminal;
        $opts['Ds_Merchant_TransactionType'] = $transactionType;
        $opts['Ds_Merchant_MerchantName'] = 'test';
        $opts['Ds_Merchant_Order'] = $orderNumber;
        $opts['Ds_Merchant_ProductDescription'] = 'Ds_Merchant_ProductDescription';
        $opts['Ds_Merchant_Titular'] = 'titular';
        $opts['Ds_Merchant_MerchantURL'] = $merchantURL;
        $opts['Ds_Merchant_UrlOK'] = 'http://www.urlok.com';
        $opts['Ds_Merchant_UrlKO'] = 'http://www.urlok.com';

        //$action = $this->debug ? 'https://sis-t.redsys.es:25443/sis/realizarPago' : 'https://sis.redsys.es/sis/realizarPago';
        $action = 'https://sis-t.redsys.es:25443/sis/realizarPago' ;
        $parameters =  array(
            'inputs' => $opts,
            'action' =>  $action,
        );
        return $this->templating->renderResponse('RedsysBundle:Redsys:process.html.twig', $parameters);
    }

    /**
     * Tries to process a payment through Redsys
     *
     * @return RedsysManager Self object
     *
     * @throws SignatureNotReceivedException
     */
    public function processResult(array $parameters)
    {
        //Check we receive all needed parameters
        $this->checkResultParameters($parameters);

        $redsysMethod = $this->redsysMethodWrapper->getRedsysMethod();

        $dsSignature           = $parameters['Ds_Signature'];
        $dsResponse            = $parameters['Ds_Response'];
        $dsAmount              = $parameters['Ds_Amount'];
        $dsOrder               = $parameters['Ds_Order'];
        $dsMerchantCode        = $parameters['Ds_MerchantCode'];
        $dsCurrency            = $parameters['Ds_Currency'];
        $dsSecret               = 'qwertyasdf0123456789';
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


    protected function shopSignature($amount, $order, $merchantCode, $currency, $transactionType, $merchantURL, $secret){

        $mensaje = $amount . $order . $merchantCode . $currency . $transactionType . $merchantURL . $secret;
        // SHA1
        return strtoupper(sha1($mensaje));

    }
    protected function expectedSignature($amount, $order, $merchantCode, $currency, $response, $secret){

        $mensaje = $amount . $order . $merchantCode . $currency . $response . $secret;
        // SHA1
        return strtoupper(sha1($mensaje));

    }

    protected function currencyTranslation($currency){

        switch($currency){
            case 'EUR':
                return '978';
            case 'USD':
                return '840';
            case 'GBP':
                return '826';
            case 'JPY':
                return '392';
            case 'ARS':
                return '032';
            case 'CAD':
                return '124';
            case 'CLF':
                return '152';
            case 'COP':
                return '170';
            case 'INR':
                return '356';
            case 'MXN':
                return '484';
            case 'PEN':
                return '604';
            case 'CHF':
                return '756';
            case 'BRL':
                return '986';
            case 'VEF':
                return '937';
            case 'TRY':
                return '949';
            default:
                throw new CurrencyNotSupportedException;
        }
    }

    protected function formatOrderNumber($orderNumber){
        //Falta comprobar que empieza por 4 numericos y que como mucho tiene 12 de longitud
        $length = strlen($orderNumber);
        $minLength = 4;

        if ($length < $minLength){
            $orderNumber = str_pad($orderNumber, $minLength, '0', STR_PAD_LEFT);
        }

        return $orderNumber;
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