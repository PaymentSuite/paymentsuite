<?php

/**
 * BeFactory PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymentCoreBundle\Event\Abstracts;

use Befactory\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;
use Befactory\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface;
use Befactory\PaymentCoreBundle\PaymentMethodInterface;
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