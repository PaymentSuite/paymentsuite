<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\Services\Redsys3dSecureBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
class Redsys3dSecureBuilderTest extends TestCase
{
    public function testAddCardholderName()
    {
        $builder = (new Redsys3dSecureBuilder())->addCardholderName(str_repeat('A', 50));

        $this->assertEquals(str_repeat('A', 45), $builder->get()['cardholderName']);
    }

    public function testAddEmail()
    {
        $builder = (new Redsys3dSecureBuilder())->addEmail(str_repeat('A', 260));

        $this->assertEquals(str_repeat('A', 254), $builder->get()['email']);
    }

    public function testAddHomePhone()
    {
        $builder = (new Redsys3dSecureBuilder())->addHomePhone(
            str_repeat('A', 4),
            str_repeat('A', 20)
        );

        $this->assertEquals([
            'cc' => str_repeat('A', 3),
            'subscriber' => str_repeat('A', 15)
        ], $builder->get()['homePhone']);
    }

    public function testAddMobilePhone()
    {
        $builder = (new Redsys3dSecureBuilder())->addMobilePhone(
            str_repeat('A', 4),
            str_repeat('A', 20)
        );

        $this->assertEquals([
            'cc' => str_repeat('A', 3),
            'subscriber' => str_repeat('A', 15)
        ], $builder->get()['mobilePhone']);
    }

    public function testAddWorkPhone()
    {
        $builder = (new Redsys3dSecureBuilder())->addWorkPhone(
            str_repeat('A', 4),
            str_repeat('A', 20)
        );

        $this->assertEquals([
            'cc' => str_repeat('A', 3),
            'subscriber' => str_repeat('A', 15)
        ], $builder->get()['workPhone']);
    }

    public function testAddShippingAddressLine1()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressLine1(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['shipAddrLine1']);
    }

    public function testAddShippingAddressLine2()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressLine2(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['shipAddrLine2']);
    }

    public function testAddShippingAddressLine3()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressLine3(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['shipAddrLine3']);
    }

    public function testAddShippingAddressCity()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressCity(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['shipAddrCity']);
    }

    public function testAddShippingAddressPostalCode()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressPostalCode(str_repeat('A', 20));

        $this->assertEquals(str_repeat('A', 16), $builder->get()['shipAddrPostCode']);
    }

    public function testAddShippingAddressState()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressState(str_repeat('A', 5));

        $this->assertEquals(str_repeat('A', 3), $builder->get()['shipAddrState']);
    }

    public function testAddShippingAddressCountry()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingAddressCountry(str_repeat('A', 5));

        $this->assertEquals(str_repeat('A', 3), $builder->get()['shipAddrCountry']);
    }

    public function testAddBillingAddressLine1()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressLine1(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['billAddrLine1']);
    }

    public function testAddBillingAddressLine2()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressLine2(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['billAddrLine2']);
    }

    public function testAddBillingAddressLine3()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressLine3(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['billAddrLine3']);
    }

    public function testAddBillingAddressCity()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressCity(str_repeat('A', 60));

        $this->assertEquals(str_repeat('A', 50), $builder->get()['billAddrCity']);
    }

    public function testAddBillingAddressPostalCode()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressPostalCode(str_repeat('A', 20));

        $this->assertEquals(str_repeat('A', 16), $builder->get()['billAddrPostCode']);
    }

    public function testAddBillingAddressState()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressState(str_repeat('A', 5));

        $this->assertEquals(str_repeat('A', 3), $builder->get()['billAddrState']);
    }

    public function testAddBillingAddressCountry()
    {
        $builder = (new Redsys3dSecureBuilder())->addBillingAddressCountry(str_repeat('A', 5));

        $this->assertEquals(str_repeat('A', 3), $builder->get()['billAddrCountry']);
    }

    /**
     * @dataProvider addressProvider
     * @param \Closure $shippingAddressBuilder
     * @param \Closure $billingAddressBuilder
     * @param string|null $expected
     */
    public function testAddressMatch(\Closure $shippingAddressBuilder, \Closure $billingAddressBuilder, ?string $expected)
    {
        $builder = new Redsys3dSecureBuilder();

        $shippingAddressBuilder($builder);
        $billingAddressBuilder($builder);

        $this->assertEquals($expected, $builder->get()['addrMatch'] ?? null);
    }

    public function addressProvider()
    {
        return [
            'Parameter addrMatch not set if no address specified' => [
                function() {},
                function() {},
                null
            ],
            'Shipping address and billing address are equals' => [
                function(Redsys3dSecureBuilder $builder) {
                    $builder
                        ->addShippingAddressLine1('line 1')
                        ->addShippingAddressLine2('line 2')
                        ->addShippingAddressLine3('line 3')
                        ->addShippingAddressCity('city')
                        ->addShippingAddressPostalCode('3333')
                        ->addShippingAddressState('SSS')
                        ->addShippingAddressCountry('CCC');
                },
                function(Redsys3dSecureBuilder $builder) {
                    $builder
                        ->addBillingAddressLine1('line 1')
                        ->addBillingAddressLine2('line 2')
                        ->addBillingAddressLine3('line 3')
                        ->addBillingAddressCity('city')
                        ->addBillingAddressPostalCode('3333')
                        ->addBillingAddressState('SSS')
                        ->addBillingAddressCountry('CCC');
                },
                'Y'
            ],
            'Shipping address and billing address are not equals' => [
                function(Redsys3dSecureBuilder $builder) {
                    $builder
                        ->addShippingAddressLine1('line 1')
                        ->addShippingAddressLine2('line 2')
                        ->addShippingAddressLine3('line 3')
                        ->addShippingAddressCity('city')
                        ->addShippingAddressPostalCode('3333')
                        ->addShippingAddressState('SSS')
                        ->addShippingAddressCountry('CCC');
                },
                function(Redsys3dSecureBuilder $builder) {
                    $builder
                        ->addBillingAddressLine1('line 4')
                        ->addBillingAddressLine2('line 5')
                        ->addBillingAddressLine3('line 6')
                        ->addBillingAddressCity('city')
                        ->addBillingAddressPostalCode('3333')
                        ->addBillingAddressState('SSS')
                        ->addBillingAddressCountry('CCC');
                },
                'N'
            ],
            'Billing address not set' => [
                function(Redsys3dSecureBuilder $builder) {
                    $builder
                        ->addShippingAddressLine1('line 1')
                        ->addShippingAddressLine2('line 2')
                        ->addShippingAddressLine3('line 3')
                        ->addShippingAddressCity('city')
                        ->addShippingAddressPostalCode('3333')
                        ->addShippingAddressState('SSS')
                        ->addShippingAddressCountry('CCC');
                },
                function(Redsys3dSecureBuilder $builder) {},
                'N'
            ],
            'Shipping address not set' => [
                function(Redsys3dSecureBuilder $builder) {},
                function(Redsys3dSecureBuilder $builder) {
                    $builder
                        ->addBillingAddressLine1('line 1')
                        ->addBillingAddressLine2('line 2')
                        ->addBillingAddressLine3('line 3')
                        ->addBillingAddressCity('city')
                        ->addBillingAddressPostalCode('3333')
                        ->addBillingAddressState('SSS')
                        ->addBillingAddressCountry('CCC');
                },
                'N'
            ]
        ];
    }

    public function testAddAuthenticationDate()
    {
        $builder = (new Redsys3dSecureBuilder())
            ->addAuthenticationDate(\DateTime::createFromFormat('Y-m-d H:i', '2020-12-04 13:30'));

        $this->assertEquals(
            '202012041330',
            $builder->get()['threeDSRequestorAuthenticationInfo']['threeDSReqAuthTimestamp']
        );
    }

    public function testAddLastPasswordChangeDate()
    {
        $builder = (new Redsys3dSecureBuilder())
            ->addLastPasswordChangeDate(\DateTime::createFromFormat('Y-m-d H:i', '2020-12-04 13:30'));

        $this->assertEquals(
            '202012041330',
            $builder->get()['acctInfo']['chAccPwChange']
        );
    }

    public function testAddLastPasswordChangeDateNullDate()
    {
        $builder = (new Redsys3dSecureBuilder())->addLastPasswordChangeDate(null);

        $parameters = $builder->get();

        $this->assertArrayNotHasKey('chAccPwChange', $parameters['acctInfo']);
        $this->assertEquals(
            '01',
            $parameters['acctInfo']['chAccPwChangeInd']
        );
    }

    /**
     * @dataProvider passwordChangeIndicatorProvider
     * @param string|null $authDate
     * @param string|null $changeDate
     * @param string|null $expected
     */
    public function testLastPasswordChangeIndicator(?string $authDate, ?string $changeDate, ?string $expected)
    {
        $builder = new Redsys3dSecureBuilder();

        if (!is_null($authDate)) {
            $builder->addAuthenticationDate(\DateTime::createFromFormat('Y-m-d', $authDate));
        }

        if (!is_null($changeDate)) {
            $builder->addLastPasswordChangeDate(\DateTime::createFromFormat('Y-m-d', $changeDate));
        }

        $this->assertEquals($expected, $builder->get()['acctInfo']['chAccPwChangeInd'] ?? null);
    }

    public function passwordChangeIndicatorProvider()
    {
        return [
            'Changed after date' => ['2020-12-07', '2020-12-08', '02'],
            'Changed on authentication date' => ['2020-12-07', '2020-12-07', '02'],
            'Changed some days ago' => ['2020-12-07', '2020-12-01', '03'],
            'Changed one month ago' => ['2020-12-07', '2020-11-07', '04'],
            'Changed two months ago' => ['2020-12-07', '2020-09-07', '05'],
            'Without change date' => ['2020-12-07', null, null],
            'Without authentication date' => [null, '2020-12-07', '03'],
        ];
    }

    public function testAddPurchaseCount()
    {
        $builder = (new Redsys3dSecureBuilder())->addPurchaseCount(4);

        $this->assertEquals(4, $builder->get()['acctInfo']['nbPurchaseAccount']);
    }

    public function testAddDayTransactionCount()
    {
        $builder = (new Redsys3dSecureBuilder())->addDayTransactionCount(4);

        $this->assertEquals(4, $builder->get()['acctInfo']['txnActivityDay']);
    }

    public function testAddYearTransactionCount()
    {
        $builder = (new Redsys3dSecureBuilder())->addYearTransactionCount(4);

        $this->assertEquals(4, $builder->get()['acctInfo']['txnActivityYear']);
    }

    /**
     * @dataProvider shippingAddressUsageProvider
     * @param \DateTime|null $date
     * @param string|null $expectedIndicator
     */
    public function testLastShippingAddressUsageDate(?\DateTime $date, ?string $expectedIndicator)
    {
        $builder = (new Redsys3dSecureBuilder())->addLastShippingAddressUsageDate($date);

        $expectedDate = $date ? $date->format('Ymd') : null;

        $this->assertEquals($expectedDate, $builder->get()['acctInfo']['shipAddressUsage'] ?? null);
        $this->assertEquals($expectedIndicator, $builder->get()['acctInfo']['shipAddressUsageInd']);
    }

    public function shippingAddressUsageProvider()
    {
        return [
            'Never used' => [null, '01'],
            'Used yesterday' => [new \DateTime('yesterday'), '02'],
            'Used a month ago' => [new \DateTime('-1 month'), '03'],
            'Used two months ago' => [new \DateTime('-2 month'), '04'],
        ];
    }

    public function testAddClientNameEqualsShippingName()
    {
        $builder = (new Redsys3dSecureBuilder())->addClientNameEqualsShippingName(true);

        $this->assertEquals('01', $builder->get()['acctInfo']['shipNameIndicator']);
    }

    public function testAddClientNameNotEqualsShippingName()
    {
        $builder = (new Redsys3dSecureBuilder())->addClientNameEqualsShippingName(false);

        $this->assertEquals('02', $builder->get()['acctInfo']['shipNameIndicator']);
    }

    public function testAddSuspiciousActivityDetected()
    {
        $builder = (new Redsys3dSecureBuilder())->addSuspiciousActivityDetected(true);

        $this->assertEquals('02', $builder->get()['acctInfo']['suspiciousAccActivity']);
    }

    public function testAddSuspiciousActivityNotDetected()
    {
        $builder = (new Redsys3dSecureBuilder())->addSuspiciousActivityDetected(false);

        $this->assertEquals('01', $builder->get()['acctInfo']['suspiciousAccActivity']);
    }

    public function testAddDeliveryEmail()
    {
        $builder = (new Redsys3dSecureBuilder())->addDeliveryEmail('paymentsuite@example.com');

        $this->assertEquals(
            'paymentsuite@example.com',
            $builder->get()['merchantRiskIndicator']['deliveryEmailAddress']
        );
    }

    /**
     * @dataProvider deliveryTimeframeProvider
     * @param int|null $days
     * @param string $expectedIndicator
     */
    public function testAddDeliveryTimeframe(?int $days, string $expectedIndicator)
    {
        $builder = (new Redsys3dSecureBuilder())->addDeliveryTimeframe($days);

        $this->assertEquals(
            $expectedIndicator,
            $builder->get()['merchantRiskIndicator']['deliveryTimeframe']
        );
    }

    public function deliveryTimeframeProvider()
    {
        return [
            'Electronic delivery' => [null, '01'],
            'Same day' => [0, '02'],
            'Next day' => [1, '03'],
            'Two days' => [2, '04'],
            'More than two days' => [3, '04'],
        ];
    }

    public function testAddGiftCardAmount()
    {
        $builder = (new Redsys3dSecureBuilder())->addGiftCardAmount(20);

        $this->assertEquals(
            20,
            $builder->get()['merchantRiskIndicator']['giftCardAmount']
        );
    }

    public function testAddGiftCardCount()
    {
        $builder = (new Redsys3dSecureBuilder())->addGiftCardCount(2);

        $this->assertEquals(
            2,
            $builder->get()['merchantRiskIndicator']['giftCardCount']
        );
    }

    public function testAddGiftCardCurrency()
    {
        $builder = (new Redsys3dSecureBuilder())->addGiftCardCurrency('EUR');

        $this->assertEquals(
            '978',
            $builder->get()['merchantRiskIndicator']['giftCardCurr']
        );
    }

    public function testAddPreOrderDate()
    {
        $builder = (new Redsys3dSecureBuilder())
            ->addPreOrderDate(\DateTime::createFromFormat('Y-m-d', '2020-12-07'));

        $this->assertEquals(
            '20201207',
            $builder->get()['merchantRiskIndicator']['preOrderDate']
        );
    }

    public function testAddPreOrderMerchandiseAvailable()
    {
        $builder = (new Redsys3dSecureBuilder())->addPreOrderMerchandiseAvailable(true);

        $this->assertEquals('01', $builder->get()['merchantRiskIndicator']['preOrderPurchaseInd']);
    }

    public function testAddPreOrderMerchandiseNotAvailable()
    {
        $builder = (new Redsys3dSecureBuilder())->addPreOrderMerchandiseAvailable(false);

        $this->assertEquals('02', $builder->get()['merchantRiskIndicator']['preOrderPurchaseInd']);
    }

    public function testAddReorderIndicatorReordered()
    {
        $builder = (new Redsys3dSecureBuilder())->addReorderIndicator(true);

        $this->assertEquals('02', $builder->get()['merchantRiskIndicator']['reorderItemsInd']);
    }

    public function testAddReorderIndicatorNotReordered()
    {
        $builder = (new Redsys3dSecureBuilder())->addReorderIndicator(false);

        $this->assertEquals('01', $builder->get()['merchantRiskIndicator']['reorderItemsInd']);
    }

    public function testAddShippingToCustomerMainAddress()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingToCustomerMainAddress();

        $this->assertEquals('01', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }

    public function testAddShippingToNotCustomerMainAddress()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingToNotCustomerMainAddress();

        $this->assertEquals('02', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }

    public function testAddShippingToNotCustomerAddress()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingToNotCustomerAddress();

        $this->assertEquals('03', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }

    public function testAddShippingToPickupPoint()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingToPickupPoint();

        $this->assertEquals('04', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }

    public function testAddShippingDigitalMerchandise()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingDigitalMerchandise();

        $this->assertEquals('05', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }

    public function testAddShippingElectronicTickets()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingElectronicTickets();

        $this->assertEquals('06', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }

    public function testAddShippingOthers()
    {
        $builder = (new Redsys3dSecureBuilder())->addShippingOthers();

        $this->assertEquals('07', $builder->get()['merchantRiskIndicator']['shipIndicator']);
    }
}
