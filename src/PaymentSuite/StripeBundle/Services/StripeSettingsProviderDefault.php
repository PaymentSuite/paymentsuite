<?php

namespace PaymentSuite\StripeBundle\Services;

use PaymentSuite\StripeBundle\Services\Interfaces\StripeSettingsProviderInterface;

class StripeSettingsProviderDefault implements StripeSettingsProviderInterface
{
    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * PaypalWebCheckoutSettingsProviderDefault constructor.
     *
     * @param string $privateKey
     * @param string $publicKey
     */
    public function __construct($privateKey, $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentName()
    {
        return 'Stripe';
    }
}
