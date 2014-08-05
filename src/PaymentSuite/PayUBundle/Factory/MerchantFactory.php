<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Factory;

use PaymentSuite\PayuBundle\Model\Merchant;

/**
 * Class MerchantFactory
 */
class MerchantFactory
{
    /**
     * @var string
     *
     * apiLogin
     */
    protected $apiLogin;

    /**
     * @var string
     *
     * apiKey
     */
    protected $apiKey;

    /**
     * Construct method
     *
     * @param string $apiLogin Merchant API login
     * @param string $apiKey   Merchant API key
     */
    public function __construct($apiLogin, $apiKey)
    {
        $this->apiLogin = $apiLogin;
        $this->apiKey = $apiKey;
    }

    /**
     * Creates an instance of Merchant model
     *
     * @return Merchant model
     */
    public function create()
    {
        $merchant = new Merchant();
        $merchant->setApiLogin($this->apiLogin);
        $merchant->setApiKey($this->apiKey);

        return $merchant;
    }
}
