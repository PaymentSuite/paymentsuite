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
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, RedsysMethodWrapper $redsysMethodWrapper, PaymentBridgeInterface $paymentBridge, $templating)
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
        //$orderNumber = '0001';
        $merchantCode    = $this->redsysMethodWrapper->getMerchantCode();
        $currency        = $this->cambioMoneda($this->paymentBridge->getCurrency());


        $extraData = $this->paymentBridge->getExtraData();


        $transactionType = $extraData['transaction_type'];
        $terminal        = $extraData['terminal'];
        $merchantURL     = 'http://redsys.dev/redsys/transaction';
        $secret          = $this->redsysMethodWrapper->getSecretKey();

        $opts = array();
        $opts['Ds_Merchant_Amount'] = $amount;
        $opts['Ds_Merchant_MerchantSignature'] = $this->firmaComercio($amount, $orderNumber, $merchantCode, $currency, $transactionType, $merchantURL, $secret);
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
     * @throws PaymentOrderNotFoundException
     */
    public function processTransaction()
    {

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $this->redsysMethodWrapper->getRedsysMethod());

        return $this;
    }
    /**
     * Validates payment, given an Id of an existing order
     *
     * @param integer $orderId Id from order to validate
     *
     * @return RedsysManager self Object
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
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }

    protected function firmaComercio($amount, $order, $merchantCode, $currency, $transactionType, $merchantURL, $secret){

        $mensaje = $amount . $order . $merchantCode . $currency . $transactionType . $merchantURL . $secret;
        // Cálculo del SHA1
        return strtoupper(sha1($mensaje));

    }
    protected function firmaRedsys($amount, $order, $merchantCode, $currency, $response, $secret){

        $mensaje = $amount . $order . $merchantCode . $currency . $response . $secret;
        // Cálculo del SHA1
        return strtoupper(sha1($mensaje));

    }

    protected function cambioMoneda($currency){
        /*
        978 – Euro
840 – Dólar
826 – Libra Esterlina
392 – Yen
032 – Peso Argentino
124 – Dólar Canadiense 152 – Peso Chileno
170 – Peso Colombiano 356 – Rupia India
484 – Nuevo Peso Mejicano 604 – Nuevos Soles
756 – Franco Suizo
986 – Real Brasileño
937 – Bolívar Venezolano 949 – Lira Turca

        si no es ninguna de estas lanzar excepcion*/
        return '978';
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
}