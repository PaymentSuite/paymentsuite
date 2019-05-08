<?php

namespace PaymentSuite\RedsysBundle\Tests;

use PaymentSuite\RedsysBundle\RedsysMethod;
use PHPUnit\Framework\TestCase;

class RedsysMethodTest extends TestCase
{
    public function testCreateEmpty()
    {
        $paymentName = 'test-name';

        $instance = RedsysMethod::createEmpty($paymentName);

        $this->assertEmpty($instance->getDsMerchantParameters());
        $this->assertEmpty($instance->getDsMerchantParametersDecoded());
        $this->assertEmpty($instance->getDsSignatureVersion());
        $this->assertEmpty($instance->getDsSignature());
        $this->assertEmpty($instance->getDsOrder());
        $this->assertEquals($paymentName, $instance->getPaymentName());
    }

    public function testCreate()
    {
        $order = '1234';
        $dsMerchantParametersDecoded = [
            'Ds_Order' => $order,
            'Ds_Response' => '0',
        ];
        $dsMerchantParameters = 'dummyParameters';
        $dsSignatureVersion = 'sha256';
        $dsSignature = 'dummySignature';

        $paymentName = 'test-payment';

        $instance = RedsysMethod::create(
            $paymentName,
            $dsMerchantParametersDecoded,
            $dsMerchantParameters,
            $dsSignatureVersion,
            $dsSignature
        );

        $this->assertEquals($dsMerchantParameters, $instance->getDsMerchantParameters());
        $this->assertEquals($dsMerchantParametersDecoded, $instance->getDsMerchantParametersDecoded());
        $this->assertEquals($dsSignatureVersion, $instance->getDsSignatureVersion());
        $this->assertEquals($dsSignature, $instance->getDsSignature());
        $this->assertEquals($order, $instance->getDsOrder());
        $this->assertEquals($paymentName, $instance->getPaymentName());
    }

    public function testIsTransactionSuccessfulReturnsFalseIfEmptyMethod()
    {
        $instance = RedsysMethod::createEmpty('test-name');

        $this->assertFalse($instance->isTransactionSuccessful());
    }

    /**
     * @dataProvider invalidResponseData
     */
    public function testIsTransactionSuccessfulReturnsFalse($value)
    {
        $instance = RedsysMethod::create('test-name', ['Ds_Response' => $value], '', '', '');

        $this->assertFalse($instance->isTransactionSuccessful());
    }

    /**
     * @dataProvider validResponseData
     */
    public function testIsTransactionSuccessfulReturnsTrue($value)
    {
        $instance = RedsysMethod::create('test-name', ['Ds_Response' => $value], '', '', '');

        $this->assertTrue($instance->isTransactionSuccessful());
    }

    public function invalidResponseData()
    {
        return [
            ['-1'],
            ['100'],
        ];
    }

    public function validResponseData()
    {
        return [
            ['0'],
            ['1'],
            ['99'],
            ['98'],
        ];
    }
}
