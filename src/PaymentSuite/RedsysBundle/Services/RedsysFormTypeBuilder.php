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

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;

use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;

/**
 * RedsysFormTypeBuilder.
 */
class RedsysFormTypeBuilder
{
    /**
     * @var RedsysUrlFactory
     *
     * URL Factory service
     */
    private $urlFactory;

    /**
     * @var PaymentBridgeRedsysInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

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
     * construct.
     *
     * @param RedsysUrlFactory             $urlFactory    URL Factory service
     * @param PaymentBridgeRedsysInterface $paymentBridge Payment bridge
     * @param FormFactory                  $formFactory   Form factory
     * @param string                       $merchantCode  merchant code
     * @param string                       $secretKey     secret key
     * @param string                       $url           gateway url
     */
    public function __construct(
        PaymentBridgeRedsysInterface $paymentBridge,
        RedsysUrlFactory $urlFactory,
        FormFactory $formFactory,
        $merchantCode,
        $secretKey,
        $url
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->urlFactory = $urlFactory;
        $this->formFactory = $formFactory;
        $this->merchantCode = $merchantCode;
        $this->secretKey = $secretKey;
        $this->url = $url;
    }

    /**
     * Builds form given return, success and fail urls.
     *
     * @return FormView
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
            $Ds_Merchant_TransactionType = $extraData['transaction_type'];
        } else {
            $Ds_Merchant_TransactionType = '0';
        }

        /**
         * Creates the return route for Redsys.
         */
        $Ds_Merchant_MerchantURL = $this
            ->urlFactory
            ->getReturnRedsysUrl();

        /**
         * Creates the return route, when coming back
         * from Redsys web checkout and proccess is Ok.
         */
        $Ds_Merchant_UrlOK = $this
            ->urlFactory
            ->getReturnUrlOkForOrderId($orderId);

        /**
         * Creates the cancel payment route, when coming back
         * from Redsys web checkout and proccess is error.
         */
        $Ds_Merchant_UrlKO = $this
            ->urlFactory
            ->getReturnUrlKoForOrderId($orderId);

        /**
         * Creates the merchant signature.
         */
        $Ds_Merchant_Amount = $this->paymentBridge->getAmount();
        $Ds_Merchant_Order = $this->formatOrderNumber(
            $this
                ->paymentBridge
                ->getOrderNumber()
        );
        $Ds_Merchant_MerchantCode = $this->merchantCode;
        $Ds_Merchant_Currency = $this->getCurrencyCodeByIso($this->paymentBridge->getCurrency());
        $Ds_Merchant_MerchantSignature = $this->shopSignature(
            $Ds_Merchant_Amount,
            $Ds_Merchant_Order,
            $Ds_Merchant_MerchantCode,
            $Ds_Merchant_Currency,
            $Ds_Merchant_TransactionType,
            $Ds_Merchant_MerchantURL,
            $this->secretKey
        );

        $Ds_Merchant_Terminal = $extraData['terminal'];

        $formBuilder
            ->setAction($this->url)
            ->setMethod('POST')
            ->add('Ds_Merchant_Amount', 'hidden', [
                'data' => $Ds_Merchant_Amount,
            ])
            ->add('Ds_Merchant_MerchantSignature', 'hidden', [
                'data' => $Ds_Merchant_MerchantSignature,
            ])
            ->add('Ds_Merchant_MerchantCode', 'hidden', [
                'data' => $this->merchantCode,
            ])
            ->add('Ds_Merchant_Currency', 'hidden', [
                'data' => $Ds_Merchant_Currency,
            ])
            ->add('Ds_Merchant_Terminal', 'hidden', [
                'data' => $Ds_Merchant_Terminal,
            ])
            ->add('Ds_Merchant_Order', 'hidden', [
                'data' => $Ds_Merchant_Order,
            ])
            ->add('Ds_Merchant_MerchantURL', 'hidden', [
                'data' => $Ds_Merchant_MerchantURL,
            ])
            ->add('Ds_Merchant_UrlOK', 'hidden', [
                'data' => $Ds_Merchant_UrlOK,
            ])
            ->add('Ds_Merchant_UrlKO', 'hidden', [
                'data' => $Ds_Merchant_UrlKO,
            ]);

        /**
         * Optional form fields.
         */
        if (array_key_exists('transaction_type', $extraData)) {
            $formBuilder->add('Ds_Merchant_TransactionType', 'hidden', [
                'data' => $Ds_Merchant_TransactionType,
            ]);
        }

        if (array_key_exists('product_description', $extraData)) {
            $formBuilder->add('Ds_Merchant_ProductDescription', 'hidden', [
                'data' => $extraData['product_description'],
            ]);
        }

        if (array_key_exists('merchant_titular', $extraData)) {
            $formBuilder->add('Ds_Merchant_Titular', 'hidden', [
                'data' => $extraData['merchant_titular'],
            ]);
        }

        if (array_key_exists('merchant_name', $extraData)) {
            $formBuilder->add('Ds_Merchant_MerchantName', 'hidden', [
                'data' => $extraData['merchant_name'],
            ]);
        }

        if (array_key_exists('merchant_data', $extraData)) {
            $formBuilder->add('Ds_Merchant_MerchantData', 'hidden', [
                'data' => $extraData['merchant_data'],
            ]);
        }

        return $formBuilder
            ->getForm()
            ->createView();
    }

    /**
     * Creates signature to be sent to Redsys.
     *
     * @param string $amount          Amount
     * @param string $order           Order number
     * @param string $merchantCode    Merchant code
     * @param string $currency        Currency
     * @param string $transactionType Transaction type
     * @param string $merchantURL     Merchant url
     * @param string $secret          Secret key
     *
     * @return string Signature
     */
    private function shopSignature(
        $amount,
        $order,
        $merchantCode,
        $currency,
        $transactionType,
        $merchantURL,
        $secret
    ) {
        return strtoupper(sha1(implode('', [
            $amount,
            $order,
            $merchantCode,
            $currency,
            $transactionType,
            $merchantURL,
            $secret,
        ])));
    }

    /**
     * Translates standard currency to Redsys currency code.
     *
     * @param string $currency Currency
     *
     * @return string Currency code
     *
     * @throws CurrencyNotSupportedException Currency not supported
     */
    private function getCurrencyCodeByIso($currency)
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
                throw new CurrencyNotSupportedException();
        }
    }

    /**
     * Formats order number to be Redsys compliant.
     *
     * @param string $orderNumber Order number
     *
     * @return string $orderNumber
     *
     * @todo Check that start with 4 numbers and that at least is 12 chars long
     */
    private function formatOrderNumber($orderNumber)
    {
        $length = strlen($orderNumber);
        $minLength = 4;

        if ($length < $minLength) {
            $orderNumber = str_pad($orderNumber, $minLength, '0', STR_PAD_LEFT);
        }

        return $orderNumber;
    }
}
