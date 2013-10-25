<?php

/**
 * BanwireGatewayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package BanwireGatewayBundle
 *
 */

namespace Scastells\BanwireGatewayBundle\Services\Wrapper;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Symfony\Component\Form\FormFactory;
use Scastells\BanwireGatewayBundle\Encryptor\RC4;

/**
 * BanwireGatewayBundle manager
 */
class BanwireGatewayTypeWrapper
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
    private $cps;


    /**
     * @var string
     * 
     * User id
     */
    private $user;


    /**
     * @var string
     *
     * url gateway banwire
     */
    private $gateway;


    /**
     * @var RC4
     *
     * method for encrypt
     */
    private $encryptor;


    /**
     * Formtype construct method
     *
     * @param FormFactory $formFactory Form factory
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     * @param $user
     * @param $cps
     * @param $gateway url gateway banwire
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridge, $user, $cps, $gateway)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridge = $paymentBridge;
        $this->user = $user;
        $this->cps = $cps;
        $this->gateway = $gateway;
        $this->encryptor = new RC4($this->cps);
    }


    /**
     * Builds form given success and fail urls
     *
     * @param $responseRoute
     * @return FormBuilder
     */
    public function buildForm($responseRoute)
    {

        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $orderId = $this->paymentBridge->getOrderId() . '#' . date('Ymdhis');
        $fields = array(
            'usuario'       => $this->user,
            'gran_total'    => $this->paymentBridge->getAmount(),
            'referencia'    => $this->paymentBridge->getOrderDescription(),
            'url_respuesta' => $responseRoute
        );
        //echo $this->cps;die();
        $formKey = $this->encryptor->encrypt(implode('',$fields));


        $formBuilder
            ->setAction($this->gateway)
            ->setMethod('POST')

            /**
             * Parameters injected by construct
             */
            ->add('usuario', 'hidden', array(
                'data'  =>  $this->user,
            ))
            ->add('gran_total', 'hidden', array(
                'data'  =>   $this->paymentBridge->getAmount(),//number_format($this->paymentBridge->getAmount(), 2) * 100
            ))
            ->add('referencia_ext', 'hidden', array(
                'data'  =>  $this->paymentBridge->getOrderDescription()
            ))
            ->add('url_respuesta', 'hidden', array(
                'data'  =>  $responseRoute
            ))
            ->add('key', 'hidden', array(
                'data'  =>  $formKey
            ))
            ->add('order_id', 'hidden', array(
                'data'  =>  $orderId
            ))
            ->add('Submit', 'hidden', array(
                'data'  =>  'Pagar',
            ))
            ;
        return $formBuilder;
    }

}