<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 *
 * Denys Pasishnyi 2013
 */

namespace PaymentSuite\StripeBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\StripeBundle\Router\StripeRoutesLoader;

/**
 * Text utilities extension
 *
 */
class StripeExtension extends Twig_Extension
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
     * @var PaymentBridgeInterface
     *
     * Currency wrapper
     */
    private $paymentBridgeInterface;

    /**
     * Construct method
     *
     * @param string                 $publicKey              Public key
     * @param FormFactory            $formFactory            Form factory
     * @param PaymentBridgeInterface $paymentBridgeInterface Payment Bridge Interface
     */
    public function __construct($publicKey, FormFactory $formFactory, PaymentBridgeInterface $paymentBridgeInterface)
    {
        $this->publicKey = $publicKey;
        $this->formFactory = $formFactory;
        $this->paymentBridgeInterface = $paymentBridgeInterface;
    }

    /**
     * Init runtime
     *
     * @param Twig_Environment $environment Twig environment
     *
     * @return StripeExtension self object
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
            new Twig_SimpleFunction('stripe_render', array($this, 'renderPaymentView')),
            new Twig_SimpleFunction('stripe_scripts', array($this, 'renderPaymentScripts'))
        );
    }

    /**
     * Render stripe form view
     *
     * @return string view html
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('stripe_view');

        return $this->environment->display('StripeBundle:Stripe:view.html.twig', array(
            'stripe_form'  =>  $formType->createView(),
            'stripe_execute_route' =>  StripeRoutesLoader::ROUTE_NAME,
        ));
    }

    /**
     * Render stripe scripts view
     *
     * @return string js code needed by Stripe behaviour
     */
    public function renderPaymentScripts()
    {
        return $this->environment->display('StripeBundle:Stripe:scripts.html.twig', array(
            'public_key'    =>  $this->publicKey,
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
        return 'payment_stripe_extension';
    }
}
