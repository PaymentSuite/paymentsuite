<?php


namespace PaymentSuite\PaylandsBundle\Event;


use PaymentSuite\PaylandsBundle\PaylandsMethod;
use Symfony\Component\EventDispatcher\Event;

class PaylandsCardValidEvent extends Event
{
    /**
     * @var PaylandsMethod
     */
    private $paylandsMethod;

    /**
     * PaylandsCardValidEvent constructor.
     *
     * @param PaylandsMethod $paylandsMethod
     */
    public function __construct(PaylandsMethod $paylandsMethod)
    {
        $this->paylandsMethod = $paylandsMethod;
    }

    /**
     * @return array
     */
    public function getPaymentMethod()
    {
        return $this->paylandsMethod;
    }
}