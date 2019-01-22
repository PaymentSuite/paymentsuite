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

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use EndelWar\GestPayWS\Data\Language;
use EndelWar\GestPayWS\Parameter\DecryptParameter;
use EndelWar\GestPayWS\Parameter\EncryptParameter;
use EndelWar\GestPayWS\Response\EncryptResponse;
use EndelWar\GestPayWS\WSCryptDecrypt;
use PaymentSuite\GestpayBundle\Services\GestpayCurrencyResolver;
use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Services\GestpayTransactionIdAssembler;
use PaymentSuite\GestpayBundle\Tests\Fixtures\DummyPaymentBridge;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class GestpayEncrypterTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayEncrypterTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        ClockMock::register(GestpayTransactionIdAssembler::class);
    }

    public function testEncrypt()
    {
        $now = 1541416836;
        ClockMock::withClockMock($now);
        $shopLogin = 'GESPAY12345';
        $apiKey = null;

        $encryptParameters = new EncryptParameter([
            'shopLogin' => $shopLogin,
            'amount' => '100.51',
            'shopTransactionId' => '123T'.$now,
            'uicCode' => 242,
            'languageId' => Language::ENGLISH,
        ]);

        $sandbox = true;
        $encryptClient = $this->prophesize(WSCryptDecrypt::class);
        $encryptClient
            ->encrypt($encryptParameters)
            ->shouldBeCalled();

        $paymentBridge = new DummyPaymentBridge();
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);
        $transactionIdAssembler = new GestpayTransactionIdAssembler($paymentBridge);

        $encrypter = new GestpayEncrypter(
            $encryptClient->reveal(),
            $paymentBridge,
            $currencyResolver,
            $transactionIdAssembler,
            $sandbox,
            $shopLogin,
            $apiKey
        );

        $encrypter->encrypt();
    }

    public function testEncryptWithCustomInfoArray()
    {
        $now = 1541416836;
        ClockMock::withClockMock($now);
        $shopLogin = 'GESPAY12345';
        $apiKey = null;

        $encryptParameters = new EncryptParameter([
            'shopLogin' => $shopLogin,
            'amount' => '100.51',
            'shopTransactionId' => '123T'.$now,
            'uicCode' => 242,
            'languageId' => Language::ENGLISH,
        ]);

        $encryptParameters->setCustomInfo(['data' => 'test']);

        $sandbox = true;
        $encryptClient = $this->prophesize(WSCryptDecrypt::class);
        $encryptClient
            ->encrypt($encryptParameters)
            ->shouldBeCalled();

        $paymentBridge = new DummyPaymentBridge(['data' => 'test']);
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);
        $transactionIdAssembler = new GestpayTransactionIdAssembler($paymentBridge);

        $encrypter = new GestpayEncrypter(
            $encryptClient->reveal(),
            $paymentBridge,
            $currencyResolver,
            $transactionIdAssembler,
            $sandbox,
            $shopLogin,
            $apiKey
        );

        $encrypter->encrypt();
    }

    public function testEncryptWithApikey()
    {
        $now = 1541416836;
        ClockMock::withClockMock($now);
        $shopLogin = 'GESPAY12345';
        $apiKey = 'dummy-apikey';

        $encryptParameters = new EncryptParameter([
            'shopLogin' => $shopLogin,
            'amount' => '100.51',
            'shopTransactionId' => '123T'.$now,
            'uicCode' => 242,
            'languageId' => Language::ENGLISH,
            'apikey' => $apiKey,
        ]);

        $sandbox = true;
        $encryptClient = $this->prophesize(WSCryptDecrypt::class);
        $encryptClient
            ->encrypt($encryptParameters)
            ->shouldBeCalled();

        $paymentBridge = new DummyPaymentBridge();
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);
        $transactionIdAssembler = new GestpayTransactionIdAssembler($paymentBridge);

        $encrypter = new GestpayEncrypter(
            $encryptClient->reveal(),
            $paymentBridge,
            $currencyResolver,
            $transactionIdAssembler,
            $sandbox,
            $shopLogin,
            $apiKey
        );

        $encrypter->encrypt();
    }

    public function testDecrypt()
    {
        $shopLogin = 'GESPAY12345';
        $apiKey = null;
        $encryptedString = 'a-dummy-encrypted-string';

        $decryptParameters = new DecryptParameter([
            'shopLogin' => $shopLogin,
            'CryptedString' => $encryptedString,
        ]);

        $sandbox = true;
        $encryptClient = $this->prophesize(WSCryptDecrypt::class);
        $encryptClient
            ->decrypt($decryptParameters)
            ->shouldBeCalled();

        $paymentBridge = new DummyPaymentBridge();
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);
        $transactionIdAssembler = new GestpayTransactionIdAssembler($paymentBridge);

        $encrypter = new GestpayEncrypter(
            $encryptClient->reveal(),
            $paymentBridge,
            $currencyResolver,
            $transactionIdAssembler,
            $sandbox,
            $shopLogin,
            $apiKey
        );

        $encrypter->decrypt($encryptedString);
    }

    public function testDecryptWithApiKey()
    {
        $shopLogin = 'GESPAY12345';
        $apiKey = 'dummy-apikey';
        $encryptedString = 'a-dummy-encrypted-string';

        $decryptParameters = new DecryptParameter([
            'shopLogin' => $shopLogin,
            'CryptedString' => $encryptedString,
            'apikey' => $apiKey,
        ]);

        $sandbox = true;
        $encryptClient = $this->prophesize(WSCryptDecrypt::class);
        $encryptClient
            ->decrypt($decryptParameters)
            ->shouldBeCalled();

        $paymentBridge = new DummyPaymentBridge();
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);
        $transactionIdAssembler = new GestpayTransactionIdAssembler($paymentBridge);

        $encrypter = new GestpayEncrypter(
            $encryptClient->reveal(),
            $paymentBridge,
            $currencyResolver,
            $transactionIdAssembler,
            $sandbox,
            $shopLogin,
            $apiKey
        );

        $encrypter->decrypt($encryptedString);
    }

    public function testEncryptedUrl()
    {
        $now = 1541416836;
        ClockMock::withClockMock($now);
        $shopLogin = 'GESPAY12345';
        $apiKey = null;
        $url = 'http://dummy-url';

        $encryptParameters = new EncryptParameter([
            'shopLogin' => $shopLogin,
            'amount' => '100.51',
            'shopTransactionId' => '123T'.$now,
            'uicCode' => 242,
            'languageId' => Language::ENGLISH,
        ]);

        $encryptedResult = $this->prophesize(EncryptResponse::class);
        $encryptedResult
            ->getPaymentPageUrl($shopLogin, 'test')
            ->shouldBeCalled()
            ->willReturn($url);

        $sandbox = true;
        $encryptClient = $this->prophesize(WSCryptDecrypt::class);
        $encryptClient
            ->encrypt($encryptParameters)
            ->shouldBeCalled()
            ->willReturn($encryptedResult->reveal());

        $paymentBridge = new DummyPaymentBridge();
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);
        $transactionIdAssembler = new GestpayTransactionIdAssembler($paymentBridge);

        $encrypter = new GestpayEncrypter(
            $encryptClient->reveal(),
            $paymentBridge,
            $currencyResolver,
            $transactionIdAssembler,
            $sandbox,
            $shopLogin,
            $apiKey
        );

        $resultUrl = $encrypter->encryptedUrl();

        $this->assertEquals($url, $resultUrl);
    }
}
