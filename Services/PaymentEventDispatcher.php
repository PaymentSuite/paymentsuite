<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymentCoreBundle\Services;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface;
use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;
use Mmoreram\PaymentCoreBundle\Event\PaymentReadyEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentDoneEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentSuccessEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentFailEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent;
use Mmoreram\PaymentCoreBundle\PaymentCoreEvents;

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

        $paymentReadyEvent = new PaymentReadyEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_READY, $paymentReadyEvent);

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

        $paymentOrderCreatedEvent = new PaymentOrderCreatedEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_ORDER_CREATED, $paymentOrderCreatedEvent);

        return $this;
    }
}
