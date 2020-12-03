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
     * @var string
     */
    private $paymentName;

    /**
     * RedsysMethod constructor.
     *
     * @param string $paymentName
     */
    private function __construct(string $paymentName)
    {
        $this->paymentName = $paymentName;
    }

    /**
     * @param string $paymentName
     *
     * @return RedsysMethod
     */
    public static function createEmpty(string $paymentName): self
    {
        return new self($paymentName);
    }

    /**
     * @param string $paymentName
     * @param array  $dsMerchantParametersDecoded
     * @param string $dsMerchantParameters
     * @param string $dsSignatureVersion
     * @param string $dsSignature
     *
     * @return RedsysMethod
     */
    public static function create(
        string $paymentName,
        array $dsMerchantParametersDecoded,
        string $dsMerchantParameters,
        string $dsSignatureVersion,
        string $dsSignature
    ): self {
        $instance = new self($paymentName);

        $instance->dsMerchantParametersDecoded = $dsMerchantParametersDecoded;
        $instance->dsMerchantParameters = $dsMerchantParameters;
        $instance->dsSignatureVersion = $dsSignatureVersion;
        $instance->dsSignature = $dsSignature;

        return $instance;
    }

    /**
     * @return array|null
     */
    public function getDsMerchantParametersDecoded(): ?array
    {
        return $this->dsMerchantParametersDecoded;
    }

    /**
     * @return string|null
     */
    public function getDsMerchantParameters(): ?string
    {
        return $this->dsMerchantParameters;
    }

    /**
     * @return string|null
     */
    public function getDsSignatureVersion(): ?string
    {
        return $this->dsSignatureVersion;
    }

    /**
     * @return string|null
     */
    public function getDsSignature(): ?string
    {
        return $this->dsSignature;
    }

    /**
     * @return bool
     */
    public function isTransactionSuccessful()
    {
        if (is_null($this->dsMerchantParametersDecoded)) {
            return false;
        }

        $dsResponse = intval($this->dsMerchantParametersDecoded['Ds_Response']);

        return $dsResponse >= 0 && $dsResponse <= 99;
    }

    /**
     * @return string|null
     */
    public function getDsOrder(): ?string
    {
        return $this->dsMerchantParametersDecoded['Ds_Order'] ?? null;
    }

    /**
     * Returns the method's type name.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }
}
