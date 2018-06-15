<?php

namespace PaymentSuite\StripeBundle\Services;

use PaymentSuite\StripeBundle\Event\StripeCustomerPreCreateEvent;
use PaymentSuite\StripeBundle\StripeEvents;
use PaymentSuite\StripeBundle\ValueObject\EditableStripeTransaction;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StripeEventDispatcher
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function notifyCustomerPreCreate(EditableStripeTransaction $transaction)
    {
        $event = new StripeCustomerPreCreateEvent($transaction);

        $this->eventDispatcher->dispatch(StripeEvents::CUSTOMER_PRE_CREATE, $event);
    }
}
