<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\StripeBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class StripeExtension extends Twig_Extension
{
    /**
     * @var PaymentBridgeInterface
     *
     * Currency wrapper
     */
    private $paymentBridgeInterface;

    /**
     * @var FormFactory
     *
     * Form factory
     */
    private $formFactory;

    /**
     * @var string
     *
     * Public key
     */
    private $publicKey;

    /**
     * @var string
     *
     * View template name in Bundle notation
     */
    private $viewTemplate;

    /**
     * @var string
     *
     * Scripts template in Bundle notation
     */
    private $scriptsTemplate;

    /**
     * Construct method
     *
     * @param PaymentBridgeInterface $paymentBridgeInterface Payment Bridge Interface
     * @param FormFactory            $formFactory            Form factory
     * @param string                 $publicKey              Public key
     * @param string                 $viewTemplate           Twig template name for displaying the form
     * @param string                 $scriptsTemplate        Twig template name for scripts/js
     */
    public function __construct(
        PaymentBridgeInterface $paymentBridgeInterface,
        FormFactory $formFactory,
        $publicKey,
        $viewTemplate,
        $scriptsTemplate
    ) {
        $this->paymentBridgeInterface = $paymentBridgeInterface;
        $this->formFactory = $formFactory;
        $this->publicKey = $publicKey;
        $this->viewTemplate = $viewTemplate;
        $this->scriptsTemplate = $scriptsTemplate;
    }

    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFunctions()
    {
        $options = ['needs_environment' => true];

        return [
            new Twig_SimpleFunction('stripe_render', function (Twig_Environment $environment, $viewTemplate = null) {

                $formType = $this->formFactory->create('stripe_view');

                $environment->display($viewTemplate ?: $this->viewTemplate, [
                    'stripe_form'          => $formType->createView(),
                    'stripe_execute_route' => 'paymentsuite_stripe_execute',
                ]);
            }, $options),
            new Twig_SimpleFunction('stripe_scripts', function (Twig_Environment $environment) {

                $environment->display($this->scriptsTemplate, [
                    'public_key' => $this->publicKey,
                    'currency'   => $this->paymentBridgeInterface->getCurrency(),
                ]);
            }, $options),
        ];
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
