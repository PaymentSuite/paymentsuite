<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\RedsysSignature;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysSettingsProviderInterface;

class RedsysSignatureFactory
{
    /**
     * @var RedsysSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * RedsysSignatureFactory constructor.
     *
     * @param RedsysSettingsProviderInterface $settingsProvider
     */
    public function __construct(RedsysSettingsProviderInterface $settingsProvider)
    {
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * @param string $signature
     *
     * @return RedsysSignature
     */
    public function createFromResultString(string $signature)
    {
        $data = RedsysEncoder::normalize($signature);

        return $this->create($data);
    }

    /**
     * @param array $parameters
     *
     * @return RedsysSignature
     */
    public function createFromMerchantParameters(array $parameters): RedsysSignature
    {
        return $this->createFromParameters($parameters, 'Ds_Merchant_Order');
    }

    /**
     * @param array $parameters
     *
     * @return RedsysSignature
     */
    public function createFromResultParameters(array $parameters): RedsysSignature
    {
        return $this->createFromParameters($parameters, 'Ds_Order');
    }

    private function create(string $data): RedsysSignature
    {
        return new RedsysSignature($data);
    }

    private function createFromParameters(array $parameters, string $orderKey): RedsysSignature
    {
        $key = $this->encrypt($parameters[$orderKey]);

        $hash = $this->hash(RedsysEncoder::encode($parameters), $key);

        return $this->create($hash);
    }

    private function encrypt(string $message): string
    {
        $key = base64_decode($this->settingsProvider->getSecretKey());

        $bytes = array(0, 0, 0, 0, 0, 0, 0, 0);
        $iv = implode(array_map('chr', $bytes));

        if (strlen($message) % 8) {
            $message = str_pad($message, strlen($message) + 8 - strlen($message) % 8, "\0");
        }

        return openssl_encrypt($message, 'DES-EDE3-CBC', $key, OPENSSL_NO_PADDING | OPENSSL_RAW_DATA, $iv);
    }

    private function hash(string $data, string $key): string
    {
        return base64_encode(hash_hmac('sha256', $data, $key, true));
    }
}
