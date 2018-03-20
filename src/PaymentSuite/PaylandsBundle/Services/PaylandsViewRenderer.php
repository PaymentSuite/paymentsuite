<?php

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\ApiClient\ApiClient;
use PaymentSuite\PaylandsBundle\ApiClient\ApiClientInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaylandsViewRenderer.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsViewRenderer
{
    /**
     * @var ApiClientInterface
     */
    protected $apiClient;

    /**
     * @var PaylandsFormFactory
     */
    protected $paymentFormFactory;

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
     * @param ApiClientInterface  $apiClient
     * @param PaylandsFormFactory $paymentFormFactory
     * @param string              $viewTemplate
     * @param string              $scriptsTemplate
     */
    public function __construct(
        ApiClientInterface $apiClient,
        PaylandsFormFactory $paymentFormFactory,
        $viewTemplate,
        $scriptsTemplate
    ) {
        $this->apiClient = $apiClient;
        $this->paymentFormFactory = $paymentFormFactory;
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
            'service' => $this->apiClient->getCurrentValidationService(),
            'template' => $this->apiClient->getTemplate(),
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
        ]);
    }
}
