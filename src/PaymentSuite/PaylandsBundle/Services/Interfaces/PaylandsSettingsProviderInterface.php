<?php

namespace PaymentSuite\PaylandsBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

interface PaylandsSettingsProviderInterface extends PaymentMethodInterface
{
    /**
     * Gets paylands API key.
     *
     * @return string
     */
    public function getApiKey(): string;

    /**
     * Gets paylands API signature.
     *
     * @return string
     */
    public function getApiSignature(): string;

    /**
     * Gets paylands validation service id.
     *
     * @return string|null
     */
    public function getValidationService(): ?string;

    /**
     * Gets paylands payment services' id by currency.
     *
     * Example:
     * [
     *     'currency' => 'EUR',
     *     'service' => '...uuid...',
     * ]
     *
     * @return array
     */
    public function getPaymentServices(): array;

    /**
     * Gets paylands i18n card templates to be shown when capturing card number.
     *
     * Example:
     * [
     *     'en' => '...uuid...',
     *     'es' => '...uuid...',
     * ]
     *
     * @return array
     */
    public function getI18nCardTemplates(): array;
}
