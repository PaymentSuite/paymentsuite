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

namespace PaymentSuite\PaylandsBundle\Services;

use WAM\Paylands\ClientInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaylandsViewRenderer.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsViewRenderer
{
    /**
     * @var ClientInterface
     */
    protected $apiClient;

    /**
     * @var PaylandsFormFactory
     */
    protected $paymentFormFactory;

    /**
     * @var PaylandsCurrencyServiceResolver
     */
    protected $currencyServiceResolver;

    /**
     * @var string
     */
    protected $viewTemplate;

    /**
     * @var string
     */
    protected $scriptsTemplate;

    /**
     * @var bool
     */
    private $sandbox;

    /**
     * PaylandsViewRenderer constructor.
     *
     * @param ClientInterface                 $apiClient
     * @param PaylandsFormFactory             $paymentFormFactory
     * @param PaylandsCurrencyServiceResolver $currencyServiceResolver
     * @param string                          $viewTemplate
     * @param string                          $scriptsTemplate
     * @param bool                            $sandbox
     */
    public function __construct(
        ClientInterface $apiClient,
        PaylandsFormFactory $paymentFormFactory,
        PaylandsCurrencyServiceResolver $currencyServiceResolver,
        $viewTemplate,
        $scriptsTemplate,
        $sandbox
    ) {
        $this->apiClient = $apiClient;
        $this->paymentFormFactory = $paymentFormFactory;
        $this->currencyServiceResolver = $currencyServiceResolver;
        $this->viewTemplate = $viewTemplate;
        $this->scriptsTemplate = $scriptsTemplate;
        $this->sandbox = $sandbox;
    }

    /**
     * @param \Twig_Environment $environment
     * @param array $options
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderView(\Twig_Environment $environment, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($options);

        $response = $this->apiClient->createCustomer($options['customer_ext_id']);

        $form = $this
            ->paymentFormFactory
            ->createForTransaction($options['customer_ext_id'], $response['Customer']['token'], (bool) $options['only_tokenize_card']);

        $renderedView = $environment->render($options['template'] ?: $this->viewTemplate, [
            'paylands_form' => $form->createView(),
        ]);

        $renderedScripts = $environment->render($this->scriptsTemplate, [
            'sandbox' => $this->sandbox,
            'service' => $this->currencyServiceResolver->getValidationService(),
            'template' => $this->apiClient->getTemplate($options['locale']),
            'additional' => $options['additional'],
        ]);

        return $renderedView.$renderedScripts;
    }

    /**
     * Set default renderer configuration.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'customer_ext_id' => uniqid(),
            'only_tokenize_card' => false,
            'additional' => '',
            'template' => null,
            'locale' => null,
        ]);
    }
}
