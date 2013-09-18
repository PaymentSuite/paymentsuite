<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author David Pujadas <dpujadas@gmail.com>
 * @package DineromailBundle
 *
 * David Pujadas 2013
 */

namespace Dpujadas\DineromailBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

use Dpujadas\DineromailBundle\Router\DineromailRoutesLoader;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class DineromailExtension extends Twig_Extension
{

    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;


    /**
     * @var CartWrapperInterface
     *
     * Cart object proxy
     */
    protected $bridge;


    /**
     * @var array
     *
     * Dineromail Config array
     */
    protected $dineromailConfig;


    /**
     * @var Twig_Environment
     *
     * Twig environment
     */
    private $environment;


    /**
     * Construct method
     *
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $bridge, array $dineromailConfig)
    {
        $this->formFactory = $formFactory;
        $this->bridge = $bridge;
        $this->dineromailConfig = $dineromailConfig;
    }


    /**
     * Init runtime
     *
     * @param Twig_Environment $environment Twig environment
     *
     * @return DineromailExtension self object
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;

        return $this;
    }


    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('dineromail_render', array($this, 'renderPaymentView'))
        );
    }


    /**
     * Render dineromail form view
     * 
     * @return string view html
     */
    public function renderPaymentView()
    {
        return $this->environment->display('DineromailBundle:Dineromail:payment.html.twig');

        $formType = $this->formFactory->createNamedBuilder(null, 'form')
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->cartWrapper->getAmount(), 2) * 100
            ))
            ->add('merchant', 'hidden', array(
                'data'  =>  $this->dineromailConfig['merchant']
            ))
            ->add('country_id', 'hidden', array(
                'data'  =>  $this->dineromailConfig['country_id']
            ))
            ->add('seller_name', 'hidden', array(
                'data'  =>  $this->dineromailConfig['seller_name']
            ))
            ->add('transaction_id', 'hidden', array(
                'data'  =>  $this->cartWrapper->getCartId().'#'.date('Ymdhis')
            ))
            ->add('language', 'hidden', array(
                'data'  =>  $this->dineromailConfig['language']
            ))
            ->add('currency', 'hidden', array(
                'data'  =>  $this->dineromailConfig['currency']
            ))
            ->add('payment_method_available', 'hidden', array(
                'data'  =>  $this->dineromailConfig['payment_method_available']
            ))
            ->add('buyer_name', 'hidden', array(
                'data'  =>  $this->cartWrapper->getBuyerName()
            ))
            ->add('buyer_lastname', 'hidden', array(
                'data'  =>  $this->cartWrapper->getBuyerLastname()
            ))
            ->add('buyer_email', 'hidden', array(
                'data'  =>  $this->cartWrapper->getBuyerEmail()
            ))
            ->add('buyer_phone', 'hidden', array(
                'data'  =>  $this->cartWrapper->getBuyerPhone()
            ))
            ->add('header_image', 'hidden', array(
                'data'  =>  $this->dineromailConfig['header_image']
            ))
            ->add('ok_url', 'hidden', array(
                'data'  =>  ''
            ))
            ->add('error_url', 'hidden', array(
                'data'  =>  ''
            ))
            ->add('pending_url', 'hidden', array(
                'data'  =>  ''
            ))
            ->add('submit', 'submit')->getForm();

        return $this->environment->display('DineromailBundle:Dineromail:view.html.twig', array(
            'dineromail_form'  =>  $formType->createView(),
            'dineromail_execute_route' =>  DineromailRoutesLoader::ROUTE_NAME,
        ));
    }


/*
            array(
                'merchant' => Configuration::get($this->config_prefix.'_MERCHANT_ID'),
                'country_id' => Configuration::get($this->config_prefix.'_COUNTRY_ID'),
                'seller_name' => htmlspecialchars(Configuration::get('PS_SHOP_NAME'))
            ),
            //'language' - assign the cart language if permitted, else defaults to English
            array(
                'transaction_id' => $this->transaction_id,
                'language' => (in_array(strtolower($current_lang), array('es', 'en', 'pt')) ? strtolower($current_lang) : 'es'),
                'currency'=> $str_currency,
                'payment_method_available' => $this->encodePayments(),
                'buyer_name' => $current_customer->firstname,
                'buyer_lastname' => $current_customer->lastname,
                'buyer_email' => $current_customer->email,
                'buyer_phone' => $current_address->phone ? $current_address->phone : $current_address->phone_mobile,
            ),
            $items,
            array(
                'header_image' => _PS_BASE_URL_._PS_IMG_.'logo.jpg',
                'ok_url' => $this->context->link->getModuleLink(
                        'dineromail',
                        'validation',
                        array('stat' => 'ok', 'cid' => $cart->id, 'tid' => $this->transaction_id ),
                        true
                    ),
                'error_url' => $this->context->link->getPageLink('history'),
                'pending_url' => $this->context->link->getModuleLink(
                    'dineromail',
                    'validation',
                    array('stat' => 'pending', 'cid' => $cart->id, 'tid' => $this->transaction_id),
                    true
                ) //1.3.x Hacks
*/

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_dineromail_extension';
    }
}