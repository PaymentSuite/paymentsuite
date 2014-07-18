<?php

/**
 * BanwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BanwireBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\BanwireBundle\Twig;

use Symfony\Component\Form\FormFactory;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class BanwireExtension extends \Twig_Extension
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    private $formFactory;

    /**
     * @var \Twig_Environment
     *
     * Twig environment
     */
    private $environment;

    /**
     * @var PaymentBridgeInterface
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
     * @param \Twig_Environment $environment Twig environment
     *
     * @return BanwireExtension self object
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
            new \Twig_SimpleFunction('banwire_render', array($this, 'renderPaymentView')),
        );
    }

    /**
     * Render banwire form view
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('banwire_view');

        $this->environment->display('BanwireBundle:Banwire:view.html.twig', array(
            'banwire_form'          =>  $formType->createView(),
        ));

    }

    /**
     * Render banwire scripts view
     */
    public function renderPaymentScripts()
    {
        $this->environment->display('BanwireBundle:Banwire:scripts.html.twig', array(
            'currency'      =>  $this->paymentBridgeInterface->getCurrency(),
        ));
    }

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_banwire_extension';
    }

}
