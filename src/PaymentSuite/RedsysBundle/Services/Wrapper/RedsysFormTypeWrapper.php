<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@gmail.com>
 *
 * Gonzalo Vilaseca 2014
 */

namespace PaymentSuite\RedsysBundle\Services\Wrapper;

use Symfony\Component\Form\FormFactory;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
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
     * @var string
     *
     * Merchant code
     */
    private $merchantCode;

    /**
     * @var string
     *
     * Secret key
     */
    private $secretKey;

    /**
     * @var string
     *
     * Url
     */
    private $url;

    /**
    * @var string
    *
    * Merchant url
    */
    protected $dsMerchantMerchantURL;

    /**
     * @var string
     *
     * Merchant url ok
     */
    protected $dsMerchantUrlOK;

    /**
     * @var string
     *
     * Merchant url ko
     */
    protected $dsMerchantUrlKO;

    /**
     * Formtype construct method
     *
     * @param FormFactory            $formFactory             Form factory
     * @param PaymentBridgeInterface $paymentBridge           Payment bridge
     * @param string                 $merchantCode            merchant code
     * @param string                 $secretKey               secret key
     * @param string                 $url                     gateway url
     * @param string                 $Ds_Merchant_MerchantURL merchant url
     * @param string                 $Ds_Merchant_UrlOK       merchant url ok
     * @param string                 $Ds_Merchant_UrlKO       merchant url ko
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
        $this->formFactory              = $formFactory;
        $this->paymentBridge            = $paymentBridge;
        $this->merchantCode             = $merchantCode;
        $this->secretKey                = $secretKey;
        $this->url                      = $url;
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

        if (array_key_exists('transaction_type', $extraData)) {
            $Ds_Merchant_TransactionType    = $extraData['transaction_type'];
        } else {
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
        if (array_key_exists('transaction_type', $extraData)) {
            $formBuilder->add('Ds_Merchant_TransactionType', 'hidden', array(
                'data' => $Ds_Merchant_TransactionType,
            ));
        }
        if (array_key_exists('product_description', $extraData)) {
            $formBuilder->add('Ds_Merchant_ProductDescription', 'hidden', array(
                'data' => $extraData['product_description'],
            ));
        }

        if (array_key_exists('merchant_titular', $extraData)) {
            $formBuilder->add('Ds_Merchant_Titular', 'hidden', array(
                'data' => $extraData['merchant_titular'],
            ));
        }

        if (array_key_exists('merchant_name', $extraData)) {
            $formBuilder->add('Ds_Merchant_MerchantName', 'hidden', array(
                'data' => $extraData['merchant_name'],
            ));
        }

        return $formBuilder->getForm()->createView();
    }

    /**
     * Creates signature to be sent to Redsys
     *
     * @param  string $amount          Amount
     * @param  string $order           Order number
     * @param  string $merchantCode    Merchant code
     * @param  string $currency        Currency
     * @param  string $transactionType Transaction type
     * @param  string $merchantURL     Merchant url
     * @param  string $secret          Secret key
     * @return string Signature
     */
    protected function shopSignature($amount, $order, $merchantCode, $currency, $transactionType, $merchantURL, $secret)
    {
        $signature = $amount . $order . $merchantCode . $currency . $transactionType . $merchantURL . $secret;
        // SHA1
        return strtoupper(sha1($signature));

    }

    /**
     * Translates standard currency to Redsys currency code
     *
     * @param  string                                                             $currency Currency
     * @return string
     * @throws \PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException
     */
    protected function currencyTranslation($currency)
    {
        switch ($currency) {
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
     * @param  string $orderNumber Order number
     * @return string $orderNumber
     */
    protected function formatOrderNumber($orderNumber)
    {
        //Falta comprobar que empieza por 4 numericos y que como mucho tiene 12 de longitud
        $length = strlen($orderNumber);
        $minLength = 4;

        if ($length < $minLength) {
            $orderNumber = str_pad($orderNumber, $minLength, '0', STR_PAD_LEFT);
        }

        return $orderNumber;
    }
}
