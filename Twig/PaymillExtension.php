<?php

/**
 * BeFactory PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Mmoreram 2013
 */

namespace Mmoreram\PaymillBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

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
     * @var string
     * 
     * Paymill controller route
     */
    private $paymillControllerRoute;


    /**
     * Construct method
     *
     * @param string      $publicKey       Public key
     * @param string      $controllerRoute Controller route
     * @param FormFactory $formFactory     Form factory
     */
    public function __construct($publicKey, $paymillControllerRoute, FormFactory $formFactory)
    {
        $this->publicKey = $publicKey;
        $this->paymillControllerRoute = $paymillControllerRoute;
        $this->formFactory = $formFactory;
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
            'paymill_form'  =>  $formType->createView(),
            'paymill_execute_route' =>  $this->paymillControllerRoute,
        ));
    }


    /**
     * Render paymill scripts view
     * 
     * @return string js code needed by Paymill behaviour
     */
    public function renderPaymentScripts()
    {

        return $this->environment->display('PaymillBundle:Paymill:scripts.html.twig', array(
            'public_key'    =>  $this->publicKey,
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