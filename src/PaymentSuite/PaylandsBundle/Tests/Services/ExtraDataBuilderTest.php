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

namespace PaymentSuite\PaylandsBundle\Tests\Services;

use PaymentSuite\PaylandsBundle\Services\ExtraDataBuilder;
use PaymentSuite\PaylandsBundle\Util\Address;
use PaymentSuite\PaylandsBundle\Util\CountryCode;
use PaymentSuite\PaylandsBundle\Util\DocumentIdentificationIssuerType;
use PaymentSuite\PaylandsBundle\Util\DocumentIdentificationType;
use PaymentSuite\PaylandsBundle\Util\Profile;
use PHPUnit\Framework\TestCase;

/**
 * Class PaylandsCurrencyServiceResolverTest.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class ExtraDataBuilderTest extends TestCase
{
    public function testBuildWithProfile()
    {
       $builder = new ExtraDataBuilder();

       $extraData = $builder->withProfile(
           Profile::create(
               'John',
               'Doe',
               'John Doe',
               'invent@mail.com',
               DocumentIdentificationIssuerType::create('STATE_GOVERNMENT'),
               DocumentIdentificationType::create('RESIDENCE_CARD')
           )
       )->build();

       $this->assertCount(1, $extraData->toArray());
       $this->assertEquals('John', $extraData->toArray()['profile']['first_name']);
       $this->assertEquals('Doe', $extraData->toArray()['profile']['last_name']);
       $this->assertEquals('John Doe', $extraData->toArray()['profile']['cardholder_name']);
       $this->assertEquals('invent@mail.com', $extraData->toArray()['profile']['email']);
       $this->assertEquals('STATE_GOVERNMENT', (string)$extraData->toArray()['profile']['document_identification_issuer_type']);
       $this->assertEquals('RESIDENCE_CARD', (string)$extraData->toArray()['profile']['document_identification_type']);
    }

    /**
     * @dataProvider getAddresses
     */
    public function testBuildWithAddress(string $builderMethod, string $extraDataIndex)
    {
        $builder = new ExtraDataBuilder();

        $extraData = $builder->$builderMethod(Address::create('Valencia', CountryCode::create('ESP'), 'Calle Invent', '46010', 'Valencia'))->build();

        $this->assertCount(1, $extraData->toArray());
        $this->assertEquals('Valencia', $extraData->toArray()[$extraDataIndex]['city']);
        $this->assertEquals('ESP', $extraData->toArray()[$extraDataIndex]['country']);
        $this->assertEquals('Calle Invent', $extraData->toArray()[$extraDataIndex]['address1']);
        $this->assertEquals('46010', $extraData->toArray()[$extraDataIndex]['zip_code']);
        $this->assertEquals('Valencia', $extraData->toArray()[$extraDataIndex]['state_code']);
    }

    public function getAddresses()
    {
        return [
            'Simple address' => ['withAddress', 'address'],
            'Shipping address' => ['withShippingAddress', 'shipping_address'],
            'Billing address' => ['withBillingAddress', 'billing_address'],
        ];
    }
}
