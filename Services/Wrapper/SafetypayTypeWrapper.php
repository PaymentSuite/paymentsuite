<?php

/**
 * SafetypayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package SafetypayBundle
 *
 */

namespace Scastells\SafetypayBundle\Services\Wrapper;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Symfony\Component\Form\FormFactory;

/**
 * SafetypayBundle manager
 */
class SafetypayTypeWrapper
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
    private $signature;

    /**
     * @var Connect
     *
     * connect Manager for SafetyPay
     */
    private $safetyPayManager;

    /**
     * @var string
     *
     * configuration value
     */
    private $expiration;


    /**
     * Formtype construct method
     *
     * @param FormFactory $formFactory Form factory
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     * @param $key
     * @param $signature
     * @param $safetyPayManager
     * @param $expiration
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridge, $key, $signature, $safetyPayManager, $expiration)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridge = $paymentBridge;
        $this->key = $key;
        $this->signature = $signature;
        $this->safetyPayManager = $safetyPayManager;
        $this->expiration = $expiration;
    }


    /**
     * Builds form given success and fail urls
     *
     * @param $responseRoute
     * @param $failRoute
     * @return FormBuilder
     */
    public function buildForm($responseRoute, $failRoute)
    {

        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $orderId = $this->paymentBridge->getOrderId() . '#' . date('Ymdhis');
        $elements = array(
            'Apikey'                => $this->key,
            'RequestDateTime'       => '',
            'CurrencyCode'          => $this->paymentBridge->getCurrency(),
            'Amount'                => $this->paymentBridge->getAmount(),
            'MerchantReferenceNo'   => $orderId,
            'Language'              => 'ES',
            'TrackingCode'          => '',
            'ExpirationTime'        => $this->expiration,
            'FilterBy'              => '',
            'TransactionOkURL'      => $responseRoute,
            'TransactionErrorURL'   => $failRoute,
            'ResponseFormat'        => $this->safetyPayManager->responseFormat
        );

        $elements['signature'] = $this->safetyPayManager->getSignature(
            $elements,
            'CurrencyCode, Amount, MerchantReferenceNo, Language,
            TrackingCode, ExpirationTime, TransactionOkURL,
            TransactionErrorURL'
        );

        $urlToken = $this->safetyPayManager->getUrlToken($elements, false);

        //controlar si el geturlToken tiene algun error!!!
        $urlTokenExploded = explode('?', $urlToken);
        $urlTokenHost = $urlTokenExploded[0];
        $urlTokenParam = $urlTokenExploded[1];
        $urlTokenParamExploded = explode('=', $urlTokenParam);

        $formBuilder
            ->setAction($this->gateway)
            ->setMethod('POST')

            /**
             * Parameters injected by construct
             */
            ->add('urlTokenHost', 'hidden', array(
                'data'  => $urlTokenHost,
            ))
            ->add('urlTokenParamName', 'hidden', array(
                'data' => $urlTokenParamExploded[0],
            ))
            ->add('urlTokenParamValue', 'hidden', array(
                'data' => $urlTokenParamExploded[1]
            ));

        return $formBuilder;
    }

}