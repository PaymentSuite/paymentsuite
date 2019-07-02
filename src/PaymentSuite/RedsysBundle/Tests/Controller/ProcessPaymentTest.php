<?php

namespace PaymentSuite\RedsysBundle\Tests\Controller;

use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;
use PaymentSuite\RedsysBundle\Services\RedsysEncoder;
use PaymentSuite\RedsysBundle\Tests\app\RedsysKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;

/**
 * Class ProcessPaymentTest.
 */
class ProcessPaymentTest extends WebTestCase
{
    private $client;

    protected static function getKernelClass()
    {
        return RedsysKernel::class;
    }


    protected function setUp()
    {
        $this->client = static::createClient();
    }

    public function testExecuteAction()
    {
        $container = $this->client->getContainer();

        /** @var PaymentBridgeRedsysInterface $paymentBridge */
        $paymentBridge = $container->get('paymentsuite.bridge');
        $paymentBridge->setOrder(new \stdClass());

        $crawler = $this->client->request('GET', '/payment/redsys/execute');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        /** @var Form $form */
        $form = $crawler->children()->last()->children()->first()->form();

        $this->assertEquals('https://sis.redsys.es/sis/realizarPago', $form->getUri());
        $this->assertEquals('POST', $form->getMethod());

        $this->assertTrue($form->has('Ds_SignatureVersion'));
        $this->assertTrue($form->has('Ds_MerchantParameters'));
        $this->assertTrue($form->has('Ds_Signature'));

        $this->assertEquals('HMAC_SHA256_V1', $form->get('Ds_SignatureVersion')->getValue());
        $this->assertNotEmpty($form->get('Ds_MerchantParameters')->getValue());
        $this->assertNotEmpty($form->get('Ds_Signature')->getValue());
    }

    public function testSuccessAction()
    {
        $this->client->request('GET', '/payment/redsys/success?id=1');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('/payment/redsys/success', $this->client->getResponse()->headers->get('Location'));
    }

    public function testFailureAction()
    {
        $this->client->request('GET', '/payment/redsys/failure?id=1');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('/payment/redsys/failure', $this->client->getResponse()->headers->get('Location'));
    }

    public function testResultActionSuccess()
    {
        $container = $this->client->getContainer();

        /** @var PaymentBridgeRedsysInterface $paymentBridge */
        $paymentBridge = $container->get('paymentsuite.bridge');
        $paymentBridge->setOrder(new \stdClass());

        $resultParameters = [
            'Ds_Order' => '1T54321',
            'Ds_Response' => '0',
        ];

        $signature = $container->get('paymentsuite.redsys.signature_factory')->createFromResultParameters($resultParameters);

        $parameters = [
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
            'Ds_Signature' => (string) $signature,
            'Ds_MerchantParameters' => RedsysEncoder::encode($resultParameters),
        ];

        $this->client->request('POST', '/payment/redsys/result', $parameters);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('/payment/redsys/success', $this->client->getResponse()->headers->get('Location'));
    }

    public function testResultActionFailsIfInvalidSignature()
    {
        $resultParameters = [
            'Ds_Order' => '1T54321',
            'Ds_Response' => '0',
        ];

        $parameters = [
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
            'Ds_Signature' => 'invalid-signature',
            'Ds_MerchantParameters' => RedsysEncoder::encode($resultParameters),
        ];

        $this->client->request('POST', '/payment/redsys/result', $parameters);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('/payment/redsys/failure', $this->client->getResponse()->headers->get('Location'));
    }

    public function testResultActionFailsIfInvalidResponse()
    {
        $resultParameters = [
            'Ds_Order' => '1T54321',
            'Ds_Response' => '100',
        ];

        $parameters = [
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
            'Ds_Signature' => 'invalid-signature',
            'Ds_MerchantParameters' => RedsysEncoder::encode($resultParameters),
        ];

        $this->client->request('POST', '/payment/redsys/result', $parameters);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('/payment/redsys/failure', $this->client->getResponse()->headers->get('Location'));
    }
}
