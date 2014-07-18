<?php

namespace PaymentSuite\PagosOnlineBundle\Twig;

use Symfony\Component\Form\FormFactory;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class PagosonlineExtension extends \Twig_Extension
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
     * @return PagosonlineExtension self object
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
            new \Twig_SimpleFunction('pagosonline_render', array($this, 'renderPaymentView')),
        );
    }

    /**
     * Render pagosonline form view
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('pagosonline_view');

        $this->environment->display('PagosonlineBundle:Pagosonline:view.html.twig', array(
            'pagosonline_form'          =>  $formType->createView(),
        ));

    }

    /**
     * Render pagosonline scripts view
     */
    public function renderPaymentScripts()
    {
        $this->environment->display('PagosonlineBundle:Pagosonline:scripts.html.twig', array(
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
        return 'payment_pagosonline_extension';
    }

}
