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
use Scastells\SafetypayBundle\Services\SafetypayManager;
use Symfony\Component\Form\FormFactory;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;

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
     * @param safetypayManager $safetyPayManager
     * @param $expiration
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridge, $key, $signature, safetypayManager $safetyPayManager, $expiration)
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
     * @param $safetyPayTransaction
     * @throws \Mmoreram\PaymentCoreBundle\Exception\PaymentException
     * @return FormBuilder
     */
    public function buildForm($responseRoute, $failRoute, $safetyPayTransaction)
    {

        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $elements = array(
            'Apikey'                => $this->key,
            'RequestDateTime'       => $this->safetyPayManager->getRequestDateTime(),
            'CurrencyCode'          => $this->paymentBridge->getCurrency(),
            'Amount'                => $this->paymentBridge->getAmount(),
            'MerchantReferenceNo'   => $safetyPayTransaction,
            'Language'              => 'ES',
            'TrackingCode'          => '',
            'ExpirationTime'        => $this->expiration,
            'FilterBy'              => '',
            'TransactionOkURL'      => $responseRoute,
            'TransactionErrorURL'   => $failRoute,
            'ResponseFormat'        => $this->safetyPayManager->getresponseFormat()
        );

        $elements['signature'] = $this->safetyPayManager->getSignature(
            $elements,
            'CurrencyCode, Amount, MerchantReferenceNo, Language,
            TrackingCode, ExpirationTime, TransactionOkURL,
            TransactionErrorURL'
        );

        $urlToken = $this->safetyPayManager->getUrlToken($elements, false);
        //Token no valid
        if (strpos($urlToken, 'Error') > 0) {
            throw new PaymentException;
        }
            $urlTokenExploded = explode('?', $urlToken);
            $urlTokenHost = $urlTokenExploded[0];
            $urlTokenParam = $urlTokenExploded[1];
            $urlTokenParamExploded = explode('=', $urlTokenParam);

        $formBuilder
            ->setAction($urlTokenHost)
            ->setMethod('POST')

            /**
             * Parameters injected by construct
             */
            ->add('TokenID', 'hidden', array(
                'data' => $urlTokenParamExploded[1],
            ));

        return $formBuilder;
    }

}