<?php

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Services\Interfaces\PaylandsSettingsProviderInterface;

class PaylandsSettingsProviderDefault implements PaylandsSettingsProviderInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiSignature;

    /**
     * @var array
     */
    private $paymentServices;

    /**
     * @var string|null
     */
    private $validationService;

    /**
     * @var array|null
     */
    private $i18nCardTemplates;

    /**
     * PaylandsSettingsProviderDefault constructor.
     *
     * @param string      $apiKey
     * @param string      $apiSignature
     * @param array       $paymentServices
     * @param array  $i18nCardTemplates
     * @param string|null $validationService
     */
    public function __construct(
        string $apiKey,
        string $apiSignature,
        array $paymentServices,
        array $i18nCardTemplates,
        ?string $validationService
    ) {
        $this->apiKey = $apiKey;
        $this->apiSignature = $apiSignature;
        $this->paymentServices = $paymentServices;
        $this->validationService = $validationService;
        $this->i18nCardTemplates = $i18nCardTemplates;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiSignature(): string
    {
        return $this->apiSignature;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationService(): ?string
    {
        return $this->validationService;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentServices(): array
    {
        return $this->paymentServices;
    }

    /**
     * {@inheritdoc}
     */
    public function getI18nCardTemplates(): array
    {
        return  $this->i18nCardTemplates;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentName(): string
    {
        return 'Paylands';
    }
}
