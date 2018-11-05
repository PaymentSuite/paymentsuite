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

namespace PaymentSuite\GestpayBundle\Tests\Integration\Services;

use EndelWar\GestPayWS\Response\DecryptResponse;
use EndelWar\GestPayWS\Response\EncryptResponse;
use PaymentSuite\GestpayBundle\Services\GestpayCurrencyResolver;
use PaymentSuite\GestpayBundle\Services\GestpayEncryptClientFactory;
use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Tests\Fixtures\DummyPaymentBridge;
use PHPUnit\Framework\TestCase;

/**
 * Class GestpayEncrypterTest
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayEncrypterTest extends TestCase
{
    /**
     * @var GestpayEncrypter
     */
    private $encrypter;

    protected function setUp()
    {
        $sandbox = true;
        $encryptClient = GestpayEncryptClientFactory::create($sandbox);
        $paymentBridge = new DummyPaymentBridge();
        $currencyResolver = new GestpayCurrencyResolver($paymentBridge);

        $shopLogin = getenv('GESTPAY_SHOP_LOGIN');
        $apiKey = getenv('GESTPAY_API_KEY') ?: null;

        $this->encrypter = new GestpayEncrypter($encryptClient, $paymentBridge, $currencyResolver, $sandbox, $shopLogin, $apiKey);
    }

    public function testEncrypt()
    {
        $enableIntegrationTests = '1' == getenv('ENABLE_API_INTEGRATION') ? true : false;

        if (!$enableIntegrationTests) {
            $this->markTestSkipped('API integration tests disabled');
        }

        $response = $this->encrypter->encrypt();

        $this->assertInstanceOf(EncryptResponse::class, $response);
        $this->assertSame('0', $response->ErrorCode);
        $this->assertNotEmpty($response->CryptDecryptString);
    }

    public function testDecrypt()
    {
        $this->markTestSkipped('API decrypt integration test disabled');

        $crypted = 'sX6lvNYXWJde4pZAiCGzFB4BL_B5kWQya46g5E0vQrjUlwWWZxEo8I4fhUFec9wM3FVsIW*JV8_R*4mxIXH_S775Ch9nPM63eIdr_67tblzJLMjOu4T8_*oaUXP5Rv0FOuV3Iu7Dg_oovUQP6yiNmpUhHgOv0jkL*sSmCbo5WizgZezBg0Bpi6SQBHimiw2l4lSGwu6v_R6eVM2npnMzLhzcbJL4srskEsd404Jh7r1k3N*P7FF2nkRGy00Nst56YkC694D4FHPTIvmrUZczn9g_YqLRwHbZ2vCRfnac1HALBa4ZLIUR_st7ZG5xJEHDsV9a_W4ulsPKJsauKqbIws7ycx5ntHhI9x6cfvd5NiC7oAEAxbL87JZ*t6wwHiY6xF8Bsl6ZoQMG499vlmdTwhEcy8aG4GG12qADavgy9HQsiSqehtzrf2jAugttGJ5iJmqZfdtqV8MT_4hkraFfHF*cbIBv8IESq*0Y0X8Psrh5k5rJC9E_Jq8uM2tFFXHhUOT7Z3r5j6028nGjiUoIBilIEdRXxZ8gIZ8AN3yQLB*Bbfvf69QyD9MJJUQnKv6PmnHpqGF7XHCXAbJlm0FQvRDue*779FjivGNQFbrgoS6Eev25X5WMDJcEHtl*fhnJ';

        $response = $this->encrypter->decrypt($crypted);

        $this->assertInstanceOf(DecryptResponse::class, $response);
        $this->assertEquals('OK', $response->TransactionResult);
        $this->assertEquals('123T1541408201', $response->ShopTransactionID);
        $this->assertEquals(100.51, $response->Amount);
    }
}
