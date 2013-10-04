<?php

/**
 * PagosonlineGatewayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PagosonlineGatewayBundle
 *
 */

namespace Scastells\PagosonlineGatewayBundle\Services\Wrapper;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Symfony\Component\Form\FormFactory;

/**
 * PagosonlineGatewayBundle manager
 */
class PagosonlineGatewayBundleTypeWrapper
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
     * Encryption key
     */
    private $key;


    /**
     * @var string
     * 
     * User id
     */
    private $userId;


    /**
     * @var  boolean
     * 
     * Seller name
     */
    private $test;


    /**
     * @var string
     *
     * url gateway pagosonline
     */
    private $gateway;


    /**
     * Formtype construct method
     *
     * @param FormFactory $formFactory Form factory
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     * @param $key encryption key
     * @param $userId user id
     * @param $test test environment
     * @param $gateway url gateway pagosonline
     *
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridge, $key, $userId, $test, $gateway)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridge = $paymentBridge;
        $this->key = $key;
        $this->userId = $userId;
        $this->test = $test;
        $this->gateway = $gateway;
    }


    /**
     * Builds form given success and fail urls
     * 
     * @param string $pagosonlineGatewaySuccessUrl      Success route url
     * @param string $pagosonlineGatewayFailUrl         Fail route url
     *
     * @return Form
     */
    public function buildForm($pagosonlineGatewaySuccessUrl, $pagosonlineGatewayFailUrl)
    {
        $extraData = $this->paymentBridge->getExtraData();
        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $formBuilder
            ->setAction($this->gateway)
            ->setMethod('POST')

            /**
             * Parameters injected by construct
             */
            ->add('userId', 'hidden', array(
                'data'  =>  $this->userId,
            ))
            ->add('OrderId', 'hidden', array(
                'data'  =>  $this->paymentBridge->getOrderId(),
            ))
            ->add('seller_name', 'hidden', array(
                'data'  =>   $this->sellerName,
            ))
            ->add('payment_method_available', 'hidden', array(
                'data'  =>  implode(';', $this->paymentMethodsAvailable),
            ))
            ->add('url_redirect_enabled', 'hidden', array(
                'data'  =>  intval($this->urlRedirectEnabled),
            ))
            ->add('header_image', 'hidden', array(
                'data'  =>  $this->headerImage,
            ))


            /**
             * Payment bridge data
             */
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->paymentBridge->getAmount(), 2) * 100
            ))

            ->add('currency', 'hidden', array(
                'data'  =>  $this->paymentBridge->getCurrency(),
            ))


            /**
             * Extra data
             */
            ->add('buyer_name', 'hidden', array(
                'data'  =>  $extraData['customer_firstname'],
            ))
            ->add('buyer_lastname', 'hidden', array(
                'data'  =>  $extraData['customer_lastname'],
            ))
            ->add('buyer_email', 'hidden', array(
                'data'  =>  $extraData['customer_email'],
            ))
            ->add('buyer_phone', 'hidden', array(
                'data'  =>  $extraData['customer_phone'],
            ))
            ->add('language', 'hidden', array(
                'data'  =>  $extraData['language'],
            ))


            /**
             * Options injected in method
             */
            ->add('ok_url', 'hidden', array(
                'data'  =>  $dineromailSuccessUrl,
            ))
            ->add('error_url', 'hidden', array(
                'data'  =>  $dineromailFailUrl,
            ))
            ->add('pending_url', 'hidden', array(
                'data'  =>  $dineromailSuccessUrl,
            ));


        $iteration = 1;

        /**
         * Every item defined in the PaymentBridge is added as a simple field
         */
        foreach ($extraData['dinero_mail_items'] as $key => $dineroMailItem) {

            $formBuilder
                ->add('item_name_' . $iteration, 'hidden', array(
                    'data'  =>  $dineroMailItem['name'],
                ))
                ->add('item_quantity_' . $iteration, 'hidden', array(
                    'data'  =>  $dineroMailItem['quantity'],
                ))

                /**
                 * ammount... 
                 */
                ->add('item_ammount_' . $iteration, 'hidden', array(
                    'data'  =>  $dineroMailItem['amount'],
                ))
                ->add('item_currency_' . $iteration, 'hidden', array(
                    'data'  =>  $this->paymentBridge->getCurrency(),
                ));

            $iteration++;
        }

        return $formBuilder;
    }

}