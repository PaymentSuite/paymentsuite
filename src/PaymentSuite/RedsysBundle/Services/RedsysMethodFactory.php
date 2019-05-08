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

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Exception\DecodeParametersException;
use PaymentSuite\RedsysBundle\Exception\InvalidSignatureException;
use PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\RedsysBundle\RedsysMethod;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysSettingsProviderInterface;

/**
 * Class RedsysMethodFactory.
 */
class RedsysMethodFactory
{
    /**
     * @var RedsysSignatureFactory
     */
    private $signatureFactory;
    /**
     * @var RedsysSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * RedsysMethodFactory constructor.
     *
     * @param RedsysSignatureFactory          $signatureFactory
     * @param RedsysSettingsProviderInterface $settingsProvider
     */
    public function __construct(
        RedsysSignatureFactory $signatureFactory,
        RedsysSettingsProviderInterface $settingsProvider
    ) {
        $this->signatureFactory = $signatureFactory;
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * Create new redsys method.
     *
     * @return RedsysMethod new instance
     */
    public function createEmpty()
    {
        return RedsysMethod::createEmpty($this->settingsProvider->getPaymentName());
    }

    /**
     * Creates a new redsys method from result parameters.
     *
     * @param array $resultParameters
     *
     * @return RedsysMethod
     *
     * @throws InvalidSignatureException
     * @throws ParameterNotReceivedException
     * @throws DecodeParametersException
     */
    public function createFromResultParameters(array $resultParameters)
    {
        $this->checkResultParameters($resultParameters);

        $dsMerchantParameters = $this->decodeMerchantParameters($resultParameters['Ds_MerchantParameters']);

        $this->validateSignature($dsMerchantParameters, $resultParameters['Ds_Signature']);

        return RedsysMethod::create(
            $this->settingsProvider->getPaymentName(),
            $dsMerchantParameters,
            $resultParameters['Ds_MerchantParameters'],
            $resultParameters['Ds_SignatureVersion'],
            $resultParameters['Ds_Signature']
        );
    }

    /**
     * @param array $parameters
     *
     * @throws ParameterNotReceivedException
     */
    private function checkResultParameters(array $parameters)
    {
        $elementsMissing = array_diff([
            'Ds_MerchantParameters',
            'Ds_Signature',
            'Ds_SignatureVersion',
        ], array_keys($parameters));

        if (!empty($elementsMissing)) {
            throw new ParameterNotReceivedException(
                implode(', ', $elementsMissing)
            );
        }
    }

    /**
     * @param array  $parameters
     * @param string $signature
     *
     * @throws InvalidSignatureException
     */
    private function validateSignature(array $parameters, string $signature)
    {
        $calculatedSignature = $this->signatureFactory->createFromResultParameters($parameters);

        if (!$calculatedSignature->match($this->signatureFactory->createFromResultString($signature))) {
            throw new InvalidSignatureException();
        }
    }

    /**
     * @param string $encodedParameters
     *
     * @return array
     *
     * @throws DecodeParametersException
     */
    private function decodeMerchantParameters(string $encodedParameters): array
    {
        $dsMerchantParameters = RedsysEncoder::decode($encodedParameters);

        if (!is_array($dsMerchantParameters)) {
            throw new DecodeParametersException();
        }

        return $dsMerchantParameters;
    }
}
