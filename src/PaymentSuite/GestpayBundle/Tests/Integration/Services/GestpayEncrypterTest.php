<?php

namespace PaymentSuite\GestpayBundle\Tests\Integration\Services;

use EndelWar\GestPayWS\Response\DecryptResponse;
use EndelWar\GestPayWS\Response\EncryptResponse;
use PaymentSuite\GestpayBundle\Services\GestpayEncrypter;
use PaymentSuite\GestpayBundle\Services\GestpayOrderIdAssembler;
use PaymentSuite\GestpayBundle\Test\WebTestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

class GestpayEncrypterTest extends WebTestCase
{
    public function testEncrypt()
    {
        /** @var GestpayEncrypter $encrypter */
        $encrypter = $this->getContainer()->get('paymentsuite.gestpay.encrypter');

        $response = $encrypter->encrypt();

        $this->assertInstanceOf(EncryptResponse::class, $response);
        $this->assertSame('0', $response->ErrorCode);
        $this->assertNotEmpty($response->CryptDecryptString);
    }

    public function testDecrypt()
    {
        $crypted = 'jexPyJcgtKf4eG*yh7kPNzG5CDjeK8PSg_LrVGOX9q1XXaqrVkOC_oa9l98QxPbOXqIfX1Ts7c16g*uGKC*vu8dDz1aYKUEw_2eQjJABMBvYssNhvpSjXhDU*GnSd9K*IqBU0TKScmaERDVqLDoo26gtpeKcd0DGRvXuroLxlKKSE1CIzPI4RG0noCAuzguCIqNAvmkRkC2_5J45Hp8M4n5qaL_4kYrM0VWOkSupH2lHfOfz1o_KQlakaqvqdya7F0As87gm4XuZeVcFF0aJPhXZ2xKPhymddxDRkixsVMNB4DnAggmtdq4eZ*_B2NsYzzUFWyCyXDLn471HGuuZebfb*6DDSxlTJBzKvNE0lYZKXdoSk8*EbJd2JUU7ZLyeG6LZjVP92cjPHP77*Ba7II1e9kSBG_zr1wOFjYCrm93abEgP9A1NzBzwvUIIATgfpodR2a7RJipeaTWY2mJ7JWPWpQ2Abo0Tei9nhYjxiyNwn1IuOhlgZg5UKYBW6t7xjg4BrLwxQuVDGK4SOKOW4_e_cfJC*K_nvZnz1UrvAljtxQjg_tlbGycJE9AmxdBuAS7lGPqXf6D_w9CStKDvJv3ABHnEjyjW_9Oue5ug**M';

        /** @var GestpayEncrypter $encrypter */
        $encrypter = $this->getContainer()->get('paymentsuite.gestpay.encrypter');

        $response = $encrypter->decrypt($crypted);

        $this->assertInstanceOf(DecryptResponse::class, $response);
        $this->assertEquals('KO', $response->TransactionResult);
        $this->assertEquals('123T1541156999', $response->ShopTransactionID);
        $this->assertEquals(100.51, $response->Amount);
    }
}
