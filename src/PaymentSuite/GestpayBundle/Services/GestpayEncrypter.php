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

namespace PaymentSuite\GestpayBundle\Services;

use EndelWar\GestPayWS\Data\Language;
use EndelWar\GestPayWS\Parameter\DecryptParameter;
use EndelWar\GestPayWS\Parameter\EncryptParameter;
use EndelWar\GestPayWS\WSCryptDecrypt;
use EndelWar\GestPayWS\Response\DecryptResponse;
use EndelWar\GestPayWS\Response\EncryptResponse;
use PaymentSuite\GestpayBundle\Services\Interfaces\PaymentBridgeGestpayInterface;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\GestpayBundle\Exception\CurrencyNotSupportedException;

/**
 * Class GestpayEncrypter.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayEncrypter
{
    const ENV_TEST = 'test';
    const ENV_PROD = 'production';

    /**
     * @var WSCryptDecrypt
     */
    private $encryptClient;
    /**
     * @var PaymentBridgeInterface
     */
    private $paymentBridge;
    /**
     * @var GestpayCurrencyResolver
     */
    private $currencyResolver;
    /**
     * @var GestpayTransactionIdAssembler
     */
    private $transactionIdAssembler;
    /**
     * @var string
     */
    private $shopLogin;
    /**
     * @var null|string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $sandbox;

    /**
     * GestpayEncrypter constructor.
     *
     * @param WSCryptDecrypt                $encryptClient
     * @param PaymentBridgeGestpayInterface $paymentBridge
     * @param GestpayCurrencyResolver       $currencyResolver
     * @param GestpayTransactionIdAssembler $transactionIdAssembler
     * @param string                        $sandbox
     * @param string                        $shopLogin
     * @param null|string                   $apiKey
     */
    public function __construct(
        WSCryptDecrypt $encryptClient,
        PaymentBridgeGestpayInterface $paymentBridge,
        GestpayCurrencyResolver $currencyResolver,
        GestpayTransactionIdAssembler $transactionIdAssembler,
        string $sandbox,
        string $shopLogin,
        ?string $apiKey
    ) {
        $this->encryptClient = $encryptClient;
        $this->paymentBridge = $paymentBridge;
        $this->currencyResolver = $currencyResolver;
        $this->transactionIdAssembler = $transactionIdAssembler;
        $this->shopLogin = $shopLogin;
        $this->apiKey = $apiKey;
        $this->sandbox = $sandbox;
    }

    /**
     * @return EncryptResponse
     *
     * @throws CurrencyNotSupportedException
     */
    public function encrypt()
    {
        $encryptParameter = new EncryptParameter([
            'shopLogin' => $this->shopLogin,
            'amount' => (string) round($this->paymentBridge->getAmount() / 100, 2),
            'shopTransactionId' => $this->transactionIdAssembler->assemble(),
            'uicCode' => $this->currencyResolver->getCurrencyCode(),
            'languageId' => Language::ENGLISH,
        ]);

        $encryptParameter->setCustomInfo($this->paymentBridge->getCustomInfo());

        if ($this->apiKey) {
            $encryptParameter->apikey = $this->apiKey;
        }

        return $this->encryptClient->encrypt($encryptParameter);
    }

    /**
     * @param string $encrypted
     *
     * @return DecryptResponse
     *
     * @throws \Exception
     */
    public function decrypt(string $encrypted)
    {
        $decryptParam = new DecryptParameter([
            'shopLogin' => $this->shopLogin,
            'CryptedString' => $encrypted,
        ]);

        if ($this->apiKey) {
            $decryptParam->apikey = $this->apiKey;
        }

        return $this->encryptClient->decrypt($decryptParam);
    }

    /**
     * @return string
     *
     * @throws CurrencyNotSupportedException
     */
    public function encryptedUrl()
    {
        $encryptResult = $this->encrypt();

        return $encryptResult->getPaymentPageUrl($this->shopLogin, $this->sandbox ? self::ENV_TEST : self::ENV_PROD);
    }
}
