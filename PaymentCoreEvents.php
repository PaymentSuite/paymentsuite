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

namespace Mmoreram\PaymentCoreBundle;

/**
 * This class define all events thrown by all payment method
 */
class PaymentCoreEvents
{

    /**
     * This event is thrown when an order must be created.
     *
     * event.name : payment.order.load
     * event.class : PaymentOrderCreatedEvent
     */
    const PAYMENT_ORDER_LOAD = 'payment.order.load';


    /**
     * This event is thrown when an order is paid, no matter the result
     *
     * event.name : payment.order.done
     * event.class : PaymentDoneEvent
     */
    const PAYMENT_ORDER_DONE = 'payment.order.done';


    /**
     * This event is thrown when an order is paid succesfuly
     *
     * event.name : payment.order.success
     * event.class : PaymentOrderSuccessEvent
     */
    const PAYMENT_ORDER_SUCCESS = 'payment.order.success';


    /**
     * This event is thrown when an order can't be paid for any reason
     *
     * event.name : payment.order.fail
     * event.class : PaymentOrderFailEvent
     */
    const PAYMENT_ORDER_FAIL = 'payment.order.fail';
}
