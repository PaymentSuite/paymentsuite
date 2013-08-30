<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\PaymillBundle\Twig;

use Befactory\CorePaymentBundle\Twig\Abstracts\AbstractExtension;
use Twig_SimpleFunction;

/**
 * Text utilities extension
 *
 */
class PaymillExtension extends AbstractExtension
{


    private $environment;
    private $publicKey;


    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }


    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
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
     * @inherit
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('paymill_view');

        return $this->environment->display('PaymillBundle:Paymill:view.html.twig', array(
            'paymill_form'  =>  $formType->createView(),
        ));
    }


    /**
     * @inherit
     */
    public function renderPaymentScripts()
    {
        /**
         * Rendering paymill pay button
         */
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