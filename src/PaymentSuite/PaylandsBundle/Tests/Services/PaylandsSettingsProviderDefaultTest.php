<?php

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Services;

use PaymentSuite\PaylandsBundle\Services\PaylandsSettingsProviderDefault;
use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutSettingsProviderDefault;
use PHPUnit\Framework\TestCase;

class PaylandsSettingsProviderDefaultTest extends TestCase
{
    public function testCreateService()
    {
        $apiKey = 'api-key';
        $apiSignature = 'me';
        $i18nCardTemplates = [
            'en' => 'template'
        ];
        $paymentServices = [
            'currency' => 'EUR',
            'service' => 'service-id'
        ];

        $validationService = 'validation-id';

        $service = new PaylandsSettingsProviderDefault(
            $apiKey,
            $apiSignature,
            $paymentServices,
            $i18nCardTemplates,
            $validationService
        );

        $this->assertEquals($apiKey, $service->getApiKey());
        $this->assertEquals($apiSignature, $service->getApiSignature());
        $this->assertEquals($paymentServices, $service->getPaymentServices());
        $this->assertEquals($validationService, $service->getValidationService());
        $this->assertEquals($i18nCardTemplates, $service->getI18nCardTemplates());
        $this->assertEquals('Paylands', $service->getPaymentName());
    }
}
