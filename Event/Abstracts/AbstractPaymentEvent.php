<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\CorePaymentBundle\Event\Abstracts;

use Befactory\CorePaymentBundle\Services\Interfaces\CartWrapperInterface;
use Befactory\CorePaymentBundle\Services\Interfaces\OrderWrapperInterface;
use Befactory\CorePaymentBundle\PaymentMethodInterface;
use Symfony\Component\EventDispatcher\Event;


/**
 * Abstract payment event
 */
abstract class AbstractPaymentEvent extends Event
{

    /**
     * @var CartWrapper
     *
     * Cart wrapper
     */
    private $cartWrapper;


    /**
     * @var OrderWrapper
     *
     * Order Wrapper
     */
    private $orderWrapper;


    /**
     * @var PaymentMethodInterface
     *
     * Payment method object
     */
    private $paymentMethod;


    /**
     * Construct method
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWr
     * @param PaymentMethodInterface $paymentMethod Payment method
     */
    public function __construct(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {
        $this->cartWrapper = $cartWrapper;
        $this->orderWrapper = $orderWrapper;
        $this->paymentMethod = $paymentMethod;
    }


    /**
     * Get Cart Wrapper
     *
     * @return CartWrapperInterface Cart Wrapper
     */
    public function getCartWrapper()
    {
        return $this->cartWrapper;
    }


    /**
     * Get Order Wrapper
     *
     * @return OrderWrapperInterface Order wrapper
     */
    public function getOrderWrapper()
    {
        return $this->orderWrapper;
    }


    /**
     * Get Payment Method
     *
     * @return PaymentMethod Payment method
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}