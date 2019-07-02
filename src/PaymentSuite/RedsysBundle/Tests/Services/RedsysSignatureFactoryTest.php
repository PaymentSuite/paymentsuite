<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\RedsysSignature;
use PaymentSuite\RedsysBundle\Services\RedsysSignatureFactory;
use PHPUnit\Framework\TestCase;

class RedsysSignatureFactoryTest extends TestCase
{
    /**
     * @var RedsysSignatureFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new RedsysSignatureFactory('very-secret');
    }

    public function testCreateFromResultParameters()
    {
        $parameters = [
            'Ds_Order' => '1234',
        ];

        $signature = $this->factory->createFromResultParameters($parameters);

        $this->assertInstanceOf(RedsysSignature::class, $signature);
        $this->assertEquals('4odzUxoEmWerdURFqh2pvmcyWM1UMmiRuY9d0OT5buY=', $signature->__toString());
    }

    public function testCreateFromMerchantParameters()
    {

        $parameters = [
            'Ds_Merchant_Order' => '1234',
        ];

        $signature = $this->factory->createFromMerchantParameters($parameters);

        $this->assertInstanceOf(RedsysSignature::class, $signature);
        $this->assertEquals('IEOi8H5k2R1mi0ydMT6le36ySpvGR9vc4zsatFnbhic=', $signature->__toString());
    }

    public function testCreateFromResultString()
    {
        $signatureStr = 'IEOi8H5k2-_R1mi0ydMT6le36ySpvGR9vc4zsatFnbhic=';

        $signature = $this->factory->createFromResultString($signatureStr);

        $this->assertInstanceOf(RedsysSignature::class, $signature);
        $this->assertEquals('IEOi8H5k2+/R1mi0ydMT6le36ySpvGR9vc4zsatFnbhic=', $signature->__toString());
    }
}
