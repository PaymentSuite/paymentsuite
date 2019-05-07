<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\RedsysBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * RedsysMethod class.
 */
final class RedsysMethod implements PaymentMethodInterface
{
    /**
     * @var array
     *
     * Decoded dsMerchantParameters array
     */
    private $dsMerchantParametersDecoded;

    /**
     * @var string
     *
     * Base64 encoded json string representation of payment parameters
     */
    private $dsMerchantParameters;

    /**
     * @var string
     *
     * Transmission version type
     */
    private $dsSignatureVersion;

    /**
     * @var string
     *
     * Encrypted payment signature
     */
    private $dsSignature;

    /**
     * RedsysMethod constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return RedsysMethod
     */
    public static function createEmpty()
    {
        return new self();
    }

    /**
     * @param array  $dsMerchantParametersDecoded
     * @param string $dsMerchantParameters
     * @param string $dsSignatureVersion
     * @param string $dsSignature
     *
     * @return RedsysMethod
     */
    public static function create(
        $dsMerchantParametersDecoded,
        $dsMerchantParameters,
        $dsSignatureVersion,
        $dsSignature
    ) {
        $instance = new self();

        $instance->dsMerchantParametersDecoded = $dsMerchantParametersDecoded;
        $instance->dsMerchantParameters = $dsMerchantParameters;
        $instance->dsSignatureVersion = $dsSignatureVersion;
        $instance->dsSignature = $dsSignature;

        return $instance;
    }

    /**
     * @return array|null
     */
    public function getDsMerchantParametersDecoded()
    {
        return $this->dsMerchantParametersDecoded;
    }

    /**
     * @return string|null
     */
    public function getDsMerchantParameters()
    {
        return $this->dsMerchantParameters;
    }

    /**
     * @return string|null
     */
    public function getDsSignatureVersion()
    {
        return $this->dsSignatureVersion;
    }

    /**
     * @return string|null
     */
    public function getDsSignature()
    {
        return $this->dsSignature;
    }

    /**
     * @return bool
     */
    public function isTransactionSuccessful()
    {
        if(is_null($this->dsMerchantParametersDecoded)){
            return false;
        }

        $dsResponse = intval($this->dsMerchantParametersDecoded['Ds_Response']);

        return $dsResponse >= 0 && $dsResponse <= 99;
    }

    /**
     * @return string|null
     */
    public function getDsOrder()
    {
        return $this->dsMerchantParametersDecoded['Ds_Order'];
    }

    /**
     * Returns the method's type name.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return 'redsys';
    }
}
