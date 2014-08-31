<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaymillBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

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
    private $formFactory;

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
     * @var PaymentBridgeInterfaces
     *
     * Payment Bridge
     */
    private $paymentBridgeInterface;

    /**
     * Construct method
     *
     * @param string                 $publicKey              Public key
     * @param FormFactory            $formFactory            Form factory
     * @param PaymentBridgeInterface $paymentBridgeInterface Payment Bridge
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
            'paymill_form'          =>  $formType->createView(),
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
        return 'payment_paymill_extension';
    }
}
