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
use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderDoneEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderSuccessEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderFailEvent;
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
     * Notifies when order must be created
     *
     * @param PaymentBridgeInterface  $paymentBridge  Payment Bridge
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentOrderCreated(PaymentBridgeInterface $paymentBridge, PaymentMethodInterface $paymentMethod)
    {

        $paymentOrderCreatedEvent = new PaymentOrderCreatedEvent($paymentBridge, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_ORDER_CREATED, $paymentOrderCreatedEvent);

        return $this;
    }


    /**
     * Notifies when order payment process is done
     *
     * It doesn't matters if process its been success or failed
     *
     * @param PaymentBridgeInterface  $paymentBridge  Payment Bridge
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentOrderDone(PaymentBridgeInterface $paymentBridge, PaymentMethodInterface $paymentMethod)
    {
        $paymentDoneEvent = new PaymentOrderDoneEvent($paymentBridge, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_ORDER_DONE, $paymentDoneEvent);

        return $this;
    }


    /**
     * Notifies when payment process is done and succeded.
     *
     * @param PaymentBridgeInterface  $paymentBridge  Payment Bridge
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentOrderSuccess(PaymentBridgeInterface $paymentBridge, PaymentMethodInterface $paymentMethod)
    {

        $PaymentOrderSuccessEvent = new PaymentOrderSuccessEvent($paymentBridge, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_ORDER_SUCCESS, $PaymentOrderSuccessEvent);

        return $this;
    }


    /**
     * Notifies when payment is done and failed
     *
     * @param PaymentBridgeInterface  $paymentBridge  Payment Bridge
     * @param PaymentMethodInterface $paymentMethod Patment method
     *
     * @return PaymentEventDispatcher self Object
     */
    public function notifyPaymentOrderFail(PaymentBridgeInterface $paymentBridge, PaymentMethodInterface $paymentMethod)
    {

        $PaymentOrderFailEvent = new PaymentOrderFailEvent($paymentBridge, $paymentMethod);
        $this->eventDispatcher->dispatch(PaymentCoreEvents::PAYMENT_ORDER_FAIL, $PaymentOrderFailEvent);

        return $this;
    }
}
