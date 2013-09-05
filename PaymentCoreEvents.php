<?php

/**
 * BeFactory PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <marc.morera@befactory.com>
 * @package PaymentCoreBundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymentCoreBundle;

/**
 * This class define all events thrown by all payment method
 */
class PaymentCoreEvents
{

    /**
     * This event is thrown when a payment is ready to be processed
     * 
     * event.name : payment.ready
     * event.class : PaymentReadyEvent
     * 
     */
    const PAYMENT_READY = 'payment.ready';


    /**
     * This event is thrown when a payment is paid, no matter the result
     *
     * event.name : payment.done
     * event.class : PaymentDoneEvent
     */
    const PAYMENT_DONE = 'payment.done';


    /**
     * This event is thrown when a payment is paid succesfuly
     *
     * event.name : payment.success
     * event.class : PaymentSuccessEvent
     */
    const PAYMENT_SUCCESS = 'payment.success';


    /**
     * This event is thrown when a payment can't be paid for any reason
     *
     * event.name : payment.fail
     * event.class : PaymentFailEvent
     */
    const PAYMENT_FAIL = 'payment.fail';


    /**
     * This event is thrown when a payment can't be paid for any reason
     *
     * event.name : payment.order.created
     * event.class : PaymentOrderCreatedEvent
     */
    const PAYMENT_ORDER_CREATED = 'payment.order.created';
}
