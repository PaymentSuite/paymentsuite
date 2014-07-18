<?php

/**
 * PaypalExpressCheckoutBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickaël Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalExpressCheckoutBundle
 *
 * Mickaël Andrieu 2014
 */

namespace PaymentSuite\PaypalExpressCheckoutBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class PaypalExpressCheckoutExtension extends Twig_Extension
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
     * @return PaypalExpressCheckoutExtension self object
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
            new Twig_SimpleFunction('paypal_express_checkout_render', array($this, 'renderPaymentView')),
        );
    }

    /**
     * Render paypal express checkout form view
     *
     * @return string view html
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('paypal_express_checkout_view');

        return $this->environment->display('PaypalExpressCheckoutBundle:PaypalExpressCheckout:view.html.twig', array(
            'paypal_express_checkout_form'          =>  $formType->createView(),
        ));
    }

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_paypal_express_checkout_extension';
    }
}
