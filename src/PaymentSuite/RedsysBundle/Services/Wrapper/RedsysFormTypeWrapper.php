<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\RedsysBundle\Services\Wrapper;

use Symfony\Component\Form\FormFactory;

use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;
use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\RedsysBundle\Services\UrlFactory;

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
     * @var PaymentBridgeRedsysInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var UrlFactory
     *
     * URL Factory service
     */
    private $urlFactory;

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
     * Formtype construct method
     *
     * @param FormFactory                  $formFactory             Form factory
     * @param PaymentBridgeRedsysInterface $paymentBridge           Payment bridge
     * @param UrlFactory                   $urlFactory              URL Factory service
     * @param string                       $merchantCode            merchant code
     * @param string                       $secretKey               secret key
     * @param string                       $url                     gateway url
     * @param string                       $Ds_Merchant_MerchantURL merchant url
     *
     */
    public function __construct(FormFactory $formFactory,
                                PaymentBridgeRedsysInterface $paymentBridge,
                                UrlFactory $urlFactory,
                                $merchantCode,
                                $secretKey,
                                $url,
                                $Ds_Merchant_MerchantURL)
    {
        $this->formFactory              = $formFactory;
        $this->paymentBridge            = $paymentBridge;
        $this->urlFactory               = $urlFactory;
        $this->merchantCode             = $merchantCode;
        $this->secretKey                = $secretKey;
        $this->url                      = $url;
        $this->Ds_Merchant_MerchantURL  = $Ds_Merchant_MerchantURL;
    }

    /**
     * Builds form given return, success and fail urls
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function buildForm()
    {
        $orderId = $this
            ->paymentBridge
            ->getOrderId();

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

        /*
         * Creates the return route, when coming back
         * from Redsys web checkout and proccess is Ok
         */
        $Ds_Merchant_UrlOK = $this
            ->urlFactory
            ->getReturnUrlOkForOrderId($orderId);

        /*
         * Creates the cancel payment route, when coming back
         * from Redsys web checkout and proccess is error
         */
        $Ds_Merchant_UrlKO = $this
            ->urlFactory
            ->getReturnUrlKoForOrderId($orderId);

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
                'data' => $Ds_Merchant_UrlOK,
            ))
            ->add('Ds_Merchant_UrlKO', 'hidden', array(
                'data' => $Ds_Merchant_UrlKO,
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