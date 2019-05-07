<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\RedsysSignature;

class RedsysSignatureFactory
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * RedsysSignatureFactory constructor.
     *
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param string $signature
     *
     * @return RedsysSignature
     */
    public function createFromResultString($signature)
    {
        $data = RedsysEncoder::normalize($signature);

        return $this->create($data);
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function createFromMerchantParameters($parameters)
    {
        return $this->createFromParameters($parameters, 'Ds_Merchant_Order');
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function createFromResultParameters($parameters)
    {
        return $this->createFromParameters($parameters, 'Ds_Order');
    }

    private function create($data)
    {
        return new RedsysSignature($data);
    }

    private function createFromParameters($parameters, $orderKey)
    {
        $key = $this->encrypt($parameters[$orderKey]);

        $hash = $this->hash(RedsysEncoder::encode($parameters), $key);

        return $this->create($hash);
    }

    private function encrypt($message)
    {
        $key = base64_decode($this->secretKey);

        $bytes = array(0, 0, 0, 0, 0, 0, 0, 0);
        $iv = implode(array_map('chr', $bytes));

        if (strlen($message) % 8) {
            $message = str_pad($message, strlen($message) + 8 - strlen($message) % 8, "\0");
        }

        return openssl_encrypt($message, 'DES-EDE3-CBC', $key, OPENSSL_NO_PADDING | OPENSSL_RAW_DATA, $iv);
    }

    private function hash($data, $key)
    {
        return base64_encode(hash_hmac('sha256', $data, $key, true));
    }
}
