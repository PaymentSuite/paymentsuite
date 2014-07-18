<?php

namespace PaymentSuite\DineroMailApiBundle\Twig;

use Symfony\Component\Form\FormFactory;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class DineromailApiExtension extends \Twig_Extension
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
     * @return DineromailApiExtension self object
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
            new \Twig_SimpleFunction('dineromail_api_render', array($this, 'renderPaymentView')),
        );
    }

    /**
     * Render dineromail form view
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('dineromail_api_view');
        $this->environment->display('DineromailApiBundle:DineromailApi:view.html.twig', array(
            'dineromail_api_form'          =>  $formType->createView(),
        ));

    }

    /**
     * Render dineromail scripts view
     */
    public function renderPaymentScripts()
    {
        $this->environment->display('DineromailApiBundle:DineromailApi:scripts.html.twig', array(
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
        return 'payment_dineromail_api_extension';
    }

}
