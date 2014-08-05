<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Model;

/**
 * Merchant Model
 */
class Merchant
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
     * Sets ApiKey
     *
     * @param string $apiKey ApiKey
     *
     * @return Merchant Self object
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get ApiKey
     *
     * @return string ApiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets ApiLogin
     *
     * @param string $apiLogin ApiLogin
     *
     * @return Merchant Self object
     */
    public function setApiLogin($apiLogin)
    {
        $this->apiLogin = $apiLogin;

        return $this;
    }

    /**
     * Get ApiLogin
     *
     * @return string ApiLogin
     */
    public function getApiLogin()
    {
        return $this->apiLogin;
    }
}
