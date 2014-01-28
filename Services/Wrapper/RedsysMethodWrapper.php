<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2014
 */

namespace PaymentSuite\RedsysBundle\Services\Wrapper;

use PaymentSuite\RedsysBundle\RedsysMethod;

/**
 * RedsysMethodWrapper
 */
class RedsysMethodWrapper
{

    /**
     * @var RedsysMethod
     *
     * Redsys method
     */
    private $redsysMethod;

    private $merchantCode;

    private $secretKey;

    private $url;

    /**
     * Construct method
     */
    public function __construct($merchantCode, $secretKey, $url)
    {
        $this->redsysMethod = new RedsysMethod;
        $this->merchantCode = $merchantCode;
        $this->secretKey    = $secretKey;
        $this->url          = $url;
    }


    /**
     * Get redsys method
     *
     * @return RedsysMethod Redsys method
     */
    public function getRedsysMethod()
    {
        return $this->redsysMethod;
    }


    /**
     * Get secret key
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }


    /**
     * Get merchant code
     *
     * @return string
     */
    public function getMerchantCode()
    {
        return $this->merchantCode;
    }
    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}