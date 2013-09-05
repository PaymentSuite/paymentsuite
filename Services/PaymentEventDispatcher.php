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

namespace Befactory\PaymentCoreBundle\Services;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Befactory\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;
use Befactory\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface;
use Befactory\PaymentCoreBundle\PaymentMethodInterface;
use Befactory\PaymentCoreBundle\Event\PaymentReadyEvent;
use Befactory\PaymentCoreBundle\Event\PaymentDoneEvent;
use Befactory\PaymentCoreBundle\Event\PaymentSuccessEvent;
use Befactory\PaymentCoreBundle\Event\PaymentFailEvent;
use Befactory\PaymentCoreBundle\Event\PaymentOrderCreatedEvent;
use Befactory\PaymentCoreBundle\PaymentCoreEvents;


/**
 * Payment event dispatcher.
 */
class PaymentEventDispatcher
{

    /**
     * @var EventDispatcher
     * 
     * Event dispatcher
     */
    private $eventDispatcher;


    /**
     * Construct method
     *
     * @param EventDispatcher $eventDispatcher Event dispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * Notifies when payment is ready.
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentReady(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentDoneEvent = new PaymentReadyEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_READY, $paymentDoneEvent);

        return $this;
    }


    /**
     * Notifies when payment process is done
     *
     * It doesn't matters if process its been success or failed
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentDone(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {
        $paymentDoneEvent = new PaymentDoneEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_DONE, $paymentDoneEvent);

        return $this;
    }


    /**
     * Notifies when payment process is done and succeded.
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentSuccess(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentSuccessEvent = new PaymentSuccessEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_SUCCESS, $paymentSuccessEvent);

        return $this;
    }


    /**
     * Notifies when payment is done and failed
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentFail(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentFailEvent = new PaymentFailEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_FAIL, $paymentFailEvent);

        return $this;
    }


    /**
     * Notifies when payment is done and failed
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentOrderCreated(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentFailEvent = new PaymentOrderCreatedEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_ORDER_CREATED, $paymentFailEvent);

        return $this;
    }
}