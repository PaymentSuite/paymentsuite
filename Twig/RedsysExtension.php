<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2014
 */

namespace PaymentSuite\RedsysBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class RedsysExtension extends Twig_Extension
{

    /**
     * @var FormFactory
     *
     * Form factory
     */
    private $formFactory;


    /**
     * @var Twig_Environment
     *
     * Twig environment
     */
    private $environment;


    /**
     * @var PaymentBridgeInterfaces
     *
     * Payment Bridge
     */
    private $paymentBridgeInterface;


    /**
     * Construct method
     *
     * @param FormFactory            $formFactory            Form factory
     * @param PaymentBridgeInterface $paymentBridgeInterface Payment Bridge
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridgeInterface)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridgeInterface = $paymentBridgeInterface;
    }


    /**
     * Init runtime
     *
     * @param Twig_Environment $environment Twig environment
     *
     * @return RedsysExtension self object
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
            new Twig_SimpleFunction('redsys_render', array($this, 'renderPaymentView')),
        );
    }


    /**
     * Render redsys form view
     *
     * @return string view html
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('redsys_view');

        return $this->environment->display('RedsysBundle:Redsys:view.html.twig', array(
            'redsys_form'          =>  $formType->createView(),
        ));
    }


    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_redsys_extension';
    }
}