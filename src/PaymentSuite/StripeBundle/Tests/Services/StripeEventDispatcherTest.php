<?php

namespace PaymentSuite\StripeBundle\Tests\Services;


use PaymentSuite\StripeBundle\Event\StripeCustomerPreCreateEvent;
use PaymentSuite\StripeBundle\Services\StripeEventDispatcher;
use PaymentSuite\StripeBundle\ValueObject\StripeTransaction;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StripeEventDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function notifyCustomerPreCreate()
    {
        $transaction = new StripeTransaction('card-token', 100, 'eur');

        $isRightEvent = function (StripeCustomerPreCreateEvent $event) use ($transaction) {

            $this->assertSame($transaction, $event->getStripeTransaction());

            return true;
        };

        $symfonyEventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $symfonyEventDispatcher
            ->dispatch('stripe.customer.pre_create', Argument::that($isRightEvent))
            ->shouldBeCalled();

        $eventDispatcher = new StripeEventDispatcher($symfonyEventDispatcher->reveal());
        $eventDispatcher->notifyCustomerPreCreate($transaction);
    }
}
