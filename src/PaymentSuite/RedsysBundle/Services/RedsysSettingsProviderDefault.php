<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysSettingsProviderInterface;

class RedsysSettingsProviderDefault implements RedsysSettingsProviderInterface
{
    /**
     * @var string
     */
    private $merchantCode;
    /**
     * @var string
     */
    private $terminal;
    /**
     * @var string
     */
    private $secretKey;

    /**
     * RedsysSettingsProviderDefault constructor.
     *
     * @param string $merchantCode
     * @param string $terminal
     * @param string $secretKey
     */
    public function __construct(string $merchantCode, string $terminal, string $secretKey)
    {
        $this->merchantCode = $merchantCode;
        $this->terminal = $terminal;
        $this->secretKey = $secretKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchanCode(): string
    {
        return $this->merchantCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getTerminal(): string
    {
        return $this->terminal;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentName(): string
    {
        return 'redsys';
    }
}
