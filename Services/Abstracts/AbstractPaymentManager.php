<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\CorePaymentBundle\Services\Abstracts;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Befactory\CorePaymentBundle\Services\Interfaces\CartWrapperInterface;
use Befactory\CorePaymentBundle\Services\Interfaces\OrderWrapperInterface;
use Befactory\CorePaymentBundle\PaymentMethodInterface;

use Befactory\CorePaymentBundle\Event\PaymentReadyEvent;
use Befactory\CorePaymentBundle\Event\PaymentDoneEvent;
use Befactory\CorePaymentBundle\Event\PaymentSuccessEvent;
use Befactory\CorePaymentBundle\Event\PaymentFailEvent;
use Befactory\CorePaymentBundle\Event\PaymentOrderCreatedEvent;

use Befactory\CorePaymentBundle\CorePaymentEvents;


/**
 * Abstract Manager class, for all payment methods managers
 *
 * This class brings managers all EventDIspatcher functionalities
 */
abstract class AbstractPaymentManager
{

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
     * @return AbstractPaymentManager self Object
     */
    protected function notifyPaymentReady(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentDoneEvent = new PaymentReadyEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(CorePaymentEvents::PAYMENT_READY, $paymentDoneEvent);

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
     * @return AbstractPaymentManager self Object
     */
    protected function notifyPaymentDone(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {
        $paymentDoneEvent = new PaymentDoneEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(CorePaymentEvents::PAYMENT_DONE, $paymentDoneEvent);

        return $this;
    }


    /**
     * Notifies when payment process is done and succeded.
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return AbstractPaymentManager self Object
     */
    protected function notifyPaymentSuccess(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentSuccessEvent = new PaymentSuccessEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(CorePaymentEvents::PAYMENT_SUCCESS, $paymentSuccessEvent);

        return $this;
    }


    /**
     * Notifies when payment is done and failed
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return AbstractPaymentManager self Object
     */
    protected function notifyPaymentFail(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentFailEvent = new PaymentFailEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(CorePaymentEvents::PAYMENT_FAIL, $paymentFailEvent);

        return $this;
    }


    /**
     * Notifies when payment is done and failed
     *
     * @param CartWrapperInterface   $cartWrapper   Cart Wrapper
     * @param OrderWrapperInterface  $orderWrapper  Order wrapper
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return AbstractPaymentManager self Object
     */
    protected function notifyPaymentOrderCreated(CartWrapperInterface $cartWrapper, OrderWrapperInterface $orderWrapper, PaymentMethodInterface $paymentMethod)
    {

        $paymentFailEvent = new PaymentOrderCreatedEvent($cartWrapper, $orderWrapper, $paymentMethod);
        $this->eventDispatcher->dispatch(CorePaymentEvents::PAYMENT_ORDER_CREATED, $paymentFailEvent);

        return $this;
    }
}