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
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
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
     * PaylandsViewRenderer constructor.
     *
     * @param ClientInterface                 $apiClient
     * @param PaylandsFormFactory             $paymentFormFactory
     * @param PaylandsCurrencyServiceResolver $currencyServiceResolver
     * @param string                          $viewTemplate
     * @param string                          $scriptsTemplate
     */
    public function __construct(
        ClientInterface $apiClient,
        PaylandsFormFactory $paymentFormFactory,
        PaylandsCurrencyServiceResolver $currencyServiceResolver,
        $viewTemplate,
        $scriptsTemplate
    ) {
        $this->apiClient = $apiClient;
        $this->paymentFormFactory = $paymentFormFactory;
        $this->currencyServiceResolver = $currencyServiceResolver;
        $this->viewTemplate = $viewTemplate;
        $this->scriptsTemplate = $scriptsTemplate;
    }

    /**
     * @param \Twig_Environment $environment
     * @param array             $options
     *
     * @return string
     */
    public function renderView(\Twig_Environment $environment, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($options);

        $response = $this->apiClient->createCustomer($options['customer_ext_id']);

        $form = $this->paymentFormFactory->create([
            'customerExternalId' => $options['customer_ext_id'],
            'customerToken' => $response['Customer']['token'],
            'onlyTokenizeCard' => (int) $options['only_tokenize_card'],
        ]);

        $renderedView = $environment->render($options['template'] ?: $this->viewTemplate, [
            'paylands_form' => $form->createView(),
        ]);

        $renderedScripts = $environment->render($this->scriptsTemplate, [
            'sandbox' => $this->apiClient->isModeSandboxEnabled(),
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
