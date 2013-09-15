<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymillBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

use Mmoreram\PaymillBundle\Router\PaymillRoutesLoader;
use Mmoreram\PaymentCoreBundle\Services\Wrapper\CurrencyWrapper;

/**
 * Text utilities extension
 *
 */
class PaymillExtension extends Twig_Extension
{

    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;


    /**
     * @var Twig_Environment
     *
     * Twig environment
     */
    private $environment;


    /**
     * @var string
     *
     * Public key
     */
    private $publicKey;


    /**
     * @var CurrencyWrapper
     * 
     * Currency wrapper
     */
    private $currency;


    /**
     * Construct method
     *
     * @param string          $publicKey   Public key
     * @param FormFactory     $formFactory Form factory
     * @param CurrencyWrapper $currency    Currency wrapper
     */
    public function __construct($publicKey, FormFactory $formFactory, CurrencyWrapper $currency)
    {
        $this->publicKey = $publicKey;
        $this->formFactory = $formFactory;
        $this->currency = $currency;
    }


    /**
     * Init runtime
     *
     * @param Twig_Environment $environment Twig environment
     *
     * @return PaymillExtension self object
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
            new Twig_SimpleFunction('paymill_render', array($this, 'renderPaymentView')),
            new Twig_SimpleFunction('paymill_scripts', array($this, 'renderPaymentScripts'))
        );
    }


    /**
     * Render paymill form view
     * 
     * @return string view html
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('paymill_view');

        return $this->environment->display('PaymillBundle:Paymill:view.html.twig', array(
            'paymill_form'          =>  $formType->createView(),
            'paymill_execute_route' =>  PaymillRoutesLoader::ROUTE_NAME,
        ));
    }


    /**
     * Render paymill scripts view
     * 
     * @param string $currency Currency
     * 
     * @return string js code needed by Paymill behaviour
     */
    public function renderPaymentScripts()
    {
        return $this->environment->display('PaymillBundle:Paymill:scripts.html.twig', array(
            'public_key'    =>  $this->publicKey,
            'currency'      =>  $this->currency->getCurrency(),
        ));
    }


    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_paymill_extension';
    }
}