<?php

namespace PaymentSuite\StripeBundle\Tests\Services;

use PaymentSuite\StripeBundle\Services\Interfaces\StripeSettingsProviderInterface;
use PaymentSuite\StripeBundle\Services\StripeEventDispatcher;
use PaymentSuite\StripeBundle\Services\StripeTransactionFactory;
use PaymentSuite\StripeBundle\ValueObject\EditableStripeTransaction;
use PaymentSuite\StripeBundle\ValueObject\StripeTransaction;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class StripeTransactionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $enableIntegrationTests = '1' == getenv('ENABLE_API_INTEGRATION') ? true : false;

        if (!$enableIntegrationTests) {
            $this->markTestSkipped('API integration tests disabled');
        }

        /** @var StripeEventDispatcher|ObjectProphecy $dispatcher */
        $dispatcher = $this->prophesize(StripeEventDispatcher::class);
        $dispatcher
            ->notifyCustomerPreCreate(Argument::type(EditableStripeTransaction::class))
            ->shouldBeCalled();

        $settingsProvider = $this->prophesize(StripeSettingsProviderInterface::class);
        $settingsProvider
            ->getPrivateKey()
            ->shouldBeCalled()
            ->willReturn(getenv('STRIPE_API_KEY'));

        $factory = new StripeTransactionFactory($settingsProvider->reveal(), $dispatcher->reveal());

        $charge = $factory->create(new StripeTransaction('tok_visa', 100, 'eur'));

        $this->assertNotNull($charge);
        $this->assertTrue($charge['paid']);
    }
}
