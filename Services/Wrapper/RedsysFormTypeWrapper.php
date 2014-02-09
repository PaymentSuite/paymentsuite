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

namespace PaymentSuite\RedsysBundle\Services\Wrapper;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Symfony\Component\Form\FormFactory;
use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;

/**
 * RedsysMethodWrapper
 */
class RedsysFormTypeWrapper
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;


    /**
     * @var PaymentBridge
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var MerchantCode
     */
    private $merchantCode;

    /**
     * @var SecretKey
     */
    private $secretKey;

    /**
     * @var Url
     */
    private $url;

    /*
    * @var dsMerchantMerchantURL
    */
    protected $dsMerchantMerchantURL;

    /*
     * @var dsMerchantUrlOK
     *
     */
    protected $dsMerchantUrlOK;

    /*
     * @ dsMerchantUrlKO
     *
     *
     */
    protected $dsMerchantUrlKO;

    /**
     * Formtype construct method
     *
     * @param FormFactory $formFactory Form factory
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     * @param $merchantCode merchant code
     * @param $secretKey secret key
     * @param $url gateway url
     *
     */
    public function __construct(FormFactory $formFactory,
                                PaymentBridgeInterface $paymentBridge,
                                $merchantCode,
                                $secretKey,
                                $url,
                                $Ds_Merchant_MerchantURL,
                                $Ds_Merchant_UrlOK,
                                $Ds_Merchant_UrlKO)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridge = $paymentBridge;
        $this->merchantCode = $merchantCode;
        $this->secretKey    = $secretKey;
        $this->url          = $url;
        $this->Ds_Merchant_MerchantURL  = $Ds_Merchant_MerchantURL;
        $this->Ds_Merchant_UrlOK        = $Ds_Merchant_UrlOK;
        $this->Ds_Merchant_UrlKO        = $Ds_Merchant_UrlKO;
    }

    /**
     * Builds form given return, success and fail urls
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function buildForm()
    {

        $extraData = $this->paymentBridge->getExtraData();
        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        if(array_key_exists('transaction_type', $extraData)){
            $Ds_Merchant_TransactionType    = $extraData['transaction_type'];
        }else{
            $Ds_Merchant_TransactionType = '0';
        }

        $Ds_Merchant_Amount             = (integer) ($this->paymentBridge->getAmount() * 100);
        $Ds_Merchant_Order              = $this->formatOrderNumber($this->paymentBridge->getOrderNumber());
        $Ds_Merchant_MerchantCode       = $this->merchantCode;
        $Ds_Merchant_Currency           = $this->currencyTranslation($this->paymentBridge->getCurrency());
        $Ds_Merchant_MerchantSignature  = $this->shopSignature(
                                                        $Ds_Merchant_Amount,
                                                        $Ds_Merchant_Order,
                                                        $Ds_Merchant_MerchantCode,
                                                        $Ds_Merchant_Currency,
                                                        $Ds_Merchant_TransactionType,
                                                        $this->Ds_Merchant_MerchantURL,
                                                        $this->secretKey);

        $Ds_Merchant_Terminal = $extraData['terminal'];

        $formBuilder
            ->setAction($this->url)
            ->setMethod('POST')

            ->add('Ds_Merchant_Amount', 'hidden', array(
                'data' => $Ds_Merchant_Amount,
            ))
            ->add('Ds_Merchant_MerchantSignature', 'hidden', array(
                'data' => $Ds_Merchant_MerchantSignature,
            ))
            ->add('Ds_Merchant_MerchantCode', 'hidden', array(
                'data' => $this->merchantCode,
            ))
            ->add('Ds_Merchant_Currency', 'hidden', array(
                'data' => $Ds_Merchant_Currency,
            ))
            ->add('Ds_Merchant_Terminal', 'hidden', array(
                'data' =>$Ds_Merchant_Terminal,
            ))
            ->add('Ds_Merchant_Order', 'hidden', array(
                'data' => $Ds_Merchant_Order,
            ))
            ->add('Ds_Merchant_MerchantURL', 'hidden', array(
                'data' => $this->Ds_Merchant_MerchantURL,
            ))
            ->add('Ds_Merchant_UrlOK', 'hidden', array(
                'data' => $this->Ds_Merchant_UrlOK,
            ))
            ->add('Ds_Merchant_UrlKO', 'hidden', array(
                'data' => $this->Ds_Merchant_UrlKO,
            ))

        ;

        /* Optional form fields */
        if(array_key_exists('transaction_type', $extraData)){
            $formBuilder->add('Ds_Merchant_TransactionType', 'hidden', array(
                'data' => $Ds_Merchant_TransactionType,
            ));
        }
        if(array_key_exists('product_description', $extraData)){
            $formBuilder->add('Ds_Merchant_ProductDescription', 'hidden', array(
                'data' => $extraData['product_description'],
            ));
        }

        if(array_key_exists('merchant_titular', $extraData)){
            $formBuilder->add('Ds_Merchant_Titular', 'hidden', array(
                'data' => $extraData['merchant_titular'],
            ));
        }

        if(array_key_exists('merchant_name', $extraData)){
            $formBuilder->add('Ds_Merchant_MerchantName', 'hidden', array(
                'data' => $extraData['merchant_name'],
            ));
        }

        return $formBuilder->getForm()->createView();
    }

    /**
     * Creates signature to be sent to Redsys
     *
     * @param $amount
     * @param $order
     * @param $merchantCode
     * @param $currency
     * @param $transactionType
     * @param $merchantURL
     * @param $secret
     * @return string
     */
    protected function shopSignature($amount, $order, $merchantCode, $currency, $transactionType, $merchantURL, $secret){

        $signature = $amount . $order . $merchantCode . $currency . $transactionType . $merchantURL . $secret;
        // SHA1
        return strtoupper(sha1($signature));

    }

    /**
     * Translates standard currency to Redsys currency code
     *
     * @param $currency
     * @return string
     * @throws \PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException
     */
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

    /**
     * Formats order number to be Redsys compliant
     *
     * @param $orderNumber
     * @return string
     */
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