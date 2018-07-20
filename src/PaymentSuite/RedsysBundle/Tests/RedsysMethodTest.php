<?php

namespace PaymentSuite\RedsysBundle\Tests;

use PaymentSuite\RedsysBundle\RedsysMethod;
use PHPUnit\Framework\TestCase;

class RedsysMethodTest extends TestCase
{
    public function testCreateEmpty()
    {
        $instance = RedsysMethod::createEmpty();

        $this->assertEmpty($instance->getDsMerchantParameters());
        $this->assertEmpty($instance->getDsMerchantParametersDecoded());
        $this->assertEmpty($instance->getDsSignatureVersion());
        $this->assertEmpty($instance->getDsSignature());
        $this->assertEmpty($instance->getDsOrder());
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

        $instance = RedsysMethod::create(
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
    }

    public function testIsTransactionSuccessfulReturnsFalseIfEmptyMethod()
    {
        $instance = RedsysMethod::createEmpty();

        $this->assertFalse($instance->isTransactionSuccessful());
    }

    /**
     * @dataProvider invalidResponseData
     */
    public function testIsTransactionSuccessfulReturnsFalse($value)
    {
        $instance = RedsysMethod::create(['Ds_Response' => $value], '', '', '');

        $this->assertFalse($instance->isTransactionSuccessful());
    }

    /**
     * @dataProvider validResponseData
     */
    public function testIsTransactionSuccessfulReturnsTrue($value)
    {
        $instance = RedsysMethod::create(['Ds_Response' => $value], '', '', '');

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
