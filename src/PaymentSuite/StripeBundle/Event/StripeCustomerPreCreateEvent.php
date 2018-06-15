<?php

namespace PaymentSuite\StripeBundle\Event;

use PaymentSuite\StripeBundle\ValueObject\EditableStripeTransaction;
use Symfony\Component\EventDispatcher\Event;

class StripeCustomerPreCreateEvent extends Event
{
    /**
     * @var EditableStripeTransaction
     */
    private $transaction;

    /**
     * StripeCustomerPreCreateEvent constructor.
     *
     * @param EditableStripeTransaction $transaction
     */
    public function __construct(EditableStripeTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getStripeTransaction()
    {
        return $this->transaction;
    }
}
