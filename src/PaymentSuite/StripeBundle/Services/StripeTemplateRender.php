<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\StripeBundle\Services;

use PaymentSuite\StripeBundle\Services\Interfaces\StripeSettingsProviderInterface;
use Symfony\Component\Form\FormFactory;
use Twig_Environment;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class StripeTemplateRender.
 */
class StripeTemplateRender
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
     * @var StripeSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * Construct method.
     *
     * @param PaymentBridgeInterface          $paymentBridgeInterface Payment Bridge Interface
     * @param FormFactory                     $formFactory            Form factory
     * @param StripeSettingsProviderInterface $settingsProvider       Settings provider
     * @param string                          $viewTemplate           Twig template name for displaying the form
     * @param string                          $scriptsTemplate        Twig template name for scripts/js
     */
    public function __construct(
        PaymentBridgeInterface $paymentBridgeInterface,
        FormFactory $formFactory,
        StripeSettingsProviderInterface $settingsProvider,
        $viewTemplate,
        $scriptsTemplate
    ) {
        $this->paymentBridgeInterface = $paymentBridgeInterface;
        $this->formFactory = $formFactory;
        $this->viewTemplate = $viewTemplate;
        $this->scriptsTemplate = $scriptsTemplate;
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * Render stripe form.
     *
     * @param Twig_Environment $environment  Environment
     * @param bool             $viewTemplate View template
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderStripeForm(Twig_Environment $environment, $viewTemplate = null)
    {
        $formType = $this->formFactory->create('stripe_view');

        $environment->display($viewTemplate ?: $this->viewTemplate, [
            'stripe_form' => $formType->createView(),
            'stripe_execute_route' => 'paymentsuite_stripe_execute',
        ]);
    }

    /**
     * Render stripe scripts.
     *
     * @param Twig_Environment $environment Environment
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderStripeScripts(Twig_Environment $environment)
    {
        $environment->display($this->scriptsTemplate, [
            'public_key' => $this->settingsProvider->getPublicKey(),
            'currency' => $this->paymentBridgeInterface->getCurrency(),
        ]);
    }
}
