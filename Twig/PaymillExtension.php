<?php

/**
 * BeFactory PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymillBundle\Twig;

use Befactory\PaymentCoreBundle\Twig\Abstracts\AbstractExtension;
use Twig_SimpleFunction;

/**
 * Text utilities extension
 *
 */
class PaymillExtension extends AbstractExtension
{

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
     * Sets public key
     *
     * @param string $publicKey Public key
     *
     * @return PaymillExtension self object
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

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