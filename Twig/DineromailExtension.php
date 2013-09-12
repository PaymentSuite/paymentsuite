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
     * Currency
     */
    private $currency;


    /**
     * Construct method
     *
     * @param string      $publicKey   Public key
     * @param FormFactory $formFactory Form factory
     * @param string      $currency    Currency
     */
    public function __construct($publicKey, FormFactory $formFactory, $currency)
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
        $formType = $this->formFactory->create('dineromail_view');

        return $this->environment->display('DineromailBundle:Dineromail:view.html.twig', array(
            'dineromail_form'  =>  $formType->createView(),
            'dineromail_execute_route' =>  DineromailRoutesLoader::ROUTE_NAME,
        ));
    }


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