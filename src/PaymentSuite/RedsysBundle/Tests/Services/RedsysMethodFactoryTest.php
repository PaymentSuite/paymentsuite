<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\Exception\DecodeParametersException;
use PaymentSuite\RedsysBundle\Exception\InvalidSignatureException;
use PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\RedsysBundle\RedsysMethod;
use PaymentSuite\RedsysBundle\RedsysSignature;
use PaymentSuite\RedsysBundle\Services\RedsysEncoder;
use PaymentSuite\RedsysBundle\Services\RedsysMethodFactory;
use PaymentSuite\RedsysBundle\Services\RedsysSignatureFactory;
use PHPUnit\Framework\TestCase;

class RedsysMethodFactoryTest extends TestCase
{

    public function testCreateEmpty()
    {
        $signatureFactory = $this->prophesize(RedsysSignatureFactory::class);

        $factory = new RedsysMethodFactory($signatureFactory->reveal());

        $method = $factory->createEmpty();

        $this->assertInstanceOf(RedsysMethod::class, $method);
        $this->assertNull($method->getDsMerchantParameters());
        $this->assertNull($method->getDsMerchantParametersDecoded());
        $this->assertNull($method->getDsSignature());
        $this->assertNull($method->getDsSignatureVersion());
    }

    public function testCreateFromResultParametersThrowsExceptionIfMissingParameter()
    {
        $signatureFactory = $this->prophesize(RedsysSignatureFactory::class);

        $factory = new RedsysMethodFactory($signatureFactory->reveal());

        $parameters = [];

        $this->expectException(ParameterNotReceivedException::class);

        $factory->createFromResultParameters($parameters);
    }

    public function testCreateFromResultParametersThrowsExceptionIfInvalidParameters()
    {
        $signatureFactory = $this->prophesize(RedsysSignatureFactory::class);

        $factory = new RedsysMethodFactory($signatureFactory->reveal());

        $parameters = [
            'Ds_MerchantParameters' => '',
            'Ds_SignatureVersion' => '',
            'Ds_Signature' => '',
        ];

        $this->expectException(DecodeParametersException::class);

        $factory->createFromResultParameters($parameters);
    }

    public function testCreateFromResultParametersThrowsExceptionIfInvalidSignature()
    {
        $merchantParameters = [
            'Ds_Order' => '1234'
        ];

        $receivedSignature = 'invalid';

        $signatureFactory = $this->prophesize(RedsysSignatureFactory::class);
        $signatureFactory
            ->createFromResultParameters($merchantParameters)
            ->shouldBeCalled()
            ->willReturn(new RedsysSignature('signature'));

        $signatureFactory
            ->createFromResultString($receivedSignature)
            ->shouldBeCalled()
            ->willReturn(new RedsysSignature($receivedSignature));

        $factory = new RedsysMethodFactory($signatureFactory->reveal());

        $parameters = [
            'Ds_MerchantParameters' => RedsysEncoder::encode($merchantParameters),
            'Ds_SignatureVersion' => 'sha256',
            'Ds_Signature' => $receivedSignature,
        ];

        $this->expectException(InvalidSignatureException::class);

        $factory->createFromResultParameters($parameters);
    }

    public function testCreateFromResultParameters()
    {
        $merchantParameters = [
            'Ds_Order' => '1234'
        ];

        $receivedSignature = 'valid';

        $signatureFactory = $this->prophesize(RedsysSignatureFactory::class);
        $signatureFactory
            ->createFromResultParameters($merchantParameters)
            ->shouldBeCalled()
            ->willReturn(new RedsysSignature($receivedSignature));

        $signatureFactory
            ->createFromResultString($receivedSignature)
            ->shouldBeCalled()
            ->willReturn(new RedsysSignature($receivedSignature));

        $factory = new RedsysMethodFactory($signatureFactory->reveal());

        $encodedMerchantParameters = RedsysEncoder::encode($merchantParameters);

        $parameters = [
            'Ds_MerchantParameters' => $encodedMerchantParameters,
            'Ds_SignatureVersion' => '',
            'Ds_Signature' => $receivedSignature,
        ];

        $method = $factory->createFromResultParameters($parameters);

        $this->assertInstanceOf(RedsysMethod::class, $method);
        $this->assertEquals($encodedMerchantParameters, $method->getDsMerchantParameters());
        $this->assertEquals($merchantParameters, $method->getDsMerchantParametersDecoded());
        $this->assertEquals($receivedSignature, $method->getDsSignature());
        $this->assertEquals('', $method->getDsSignatureVersion());
    }
}
