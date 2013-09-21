Install PaymentCoreBundle
-----

1. [About Payment Bridge Bundle](#about-payment-bridge-bundle)
2. [Cart Wrapper](#cart-wrapper)
3. [Currency](#currency)
4. [Order Wrapper](#order-wrapper)
5. [Payment Event Listener](#payment-event-listener)


# About PaymentBridgeBundle

As Payment Suite should be compatible with all E-commerces projects, it's built without any kind of attachment with your model, so you must build (just once) a specific bridge bundle to tell Payment Suite where to find some data.

To do this, [create a Bundle](http://symfony.com/doc/current/bundles/SensioGeneratorBundle/commands/generate_bundle.html) named PaymentBridgeBundle with next classes.

## Payment Service

Payment Service is only necessary [services](http://symfony.com/doc/current/book/service_container.html) to implement. First create next class of this service which **must** implements `Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface`.

    <?php

    namespace YourProjectName\PaymentCoreBundle\Services\interfaces;

    use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

    class PaymentBridge implements PaymentBridgeInterface
    {

        /**
         * @var Order
         *
         * Order object
         */
        private $order;


        /**
         * Set order to OrderWrapper
         *
         * @var Object $order Order element
         */
        public function setOrder($order)
        {
            $this->order = $order;
        }


        /**
         * Return order
         *
         * @return Object order
         */
        public function getOrder()
        {
            return $this->order;
        }


        /**
         * Return order identifier value
         *
         * @return integer
         */
        public function getOrderDescription()
        {
            return '';
        }


        /**
         * Return order identifier value
         *
         * @return integer
         */
        public function getOrderId()
        {
            return $this->order->getId();
        }


        /**
         * Given an id, find Order
         *
         * @return Object order
         */
        public function findOrder($orderId)
        {
            /*
            * Your code to get Order
            */

            return $this->order;
        }


        /**
         * Get currency
         *
         * @return string
         */
        public function getCurrency()
        {
            /*
            * Set your static or dynamic currency
            */

            return 'USD';
        }


        /**
         * Get amount
         *
         * @return integer
         */
        public function getAmount()
        {
            /*
            * Return payment amount (in cents)
            */

            return $amount;
        }


        /**
         * Get extra data
         *
         * @return array
         */
        public function getExtraData()
        {
            return array();
        }
    } ?>


Then registry this service which **must** be named `payment.bridge` adding next code to `Resources\config\services.yml`.

`
services:
    /* ... */

    payment.bridge:
        class: YourProjectName\PaymentBridgeBundle\Services\PaymentBridge
`



## Payment Event Listener

You can [create an Event Listener](http://symfony.com/doc/current/cookbook/service_container/event_listener.html) to subscribe to Payment process events.

In fact, this will be the way to manage your cart and your order in every payment stage. To do this you must create next class with all only needed to your project methods.

<?php

namespace YourProjectName\PaymentBridgeBundle\EventListener;

use Mmoreram\PaymentCoreBundle\Event\PaymentOrderDoneEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderLoadEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderSuccessEvent;
use Mmoreram\PaymentCoreBundle\Event\PaymentOrderFailEvent;

/**
 * Payment event listener
 *
 * This listener is enabled whatever the payment method is.
 */
class Payment
{

    /**
     * On payment done event
     *
     * @param PaymentOrderDoneEvent $paymentOrderDoneEvent Payment Order Done event
     */
    public function onPaymentDone(PaymentOrderDoneEvent $paymentOrderDoneEvent)
    {
        /*
         * Your code for this event
         */
    }


    /**
     * On payment load event
     *
     * @param PaymentOrderLoadEvent $paymentOrderLoadEvent Payment Order Load event
     */
    public function onPaymentLoad(PaymentOrderLoadEvent $paymentOrderLoadEvent)
    {
        /*
         * Your code for this event
         */
    }


    /**
     * On payment success event
     *
     * @param PaymentOrderSuccessEvent $paymentOrderSuccessEvent Payment Order Success event
     */
    public function onPaymentSuccess(PaymentOrderSuccessEvent $paymentOrderSuccessEvent)
    {
        /*
         * Your code for this event
         */
    }


    /**
     * On payment fail event
     *
     * @param PaymentOrderFailEvent $paymentOrderFailEvent Payment Order Fail event
     */
    public function onPaymentFail(PaymentOrderFailEvent $paymentOrderFailEvent)
    {
        /*
         * Your code for this event
         */
    }
} ?>


Also you need registry listener for the events in `Resources\config\services.yml` adding next code.

`
services:
    /* ... */

    payment.event.listener:
        class: YourProjectName\PaymentBridgeBundle\EventListener\Payment
        arguments:
            entity.manager: "@doctrine.orm.entity_manager"
            mailer: @mailer
        tags:
            - { name: kernel.event_listener, event: payment.order.done, method: onPaymentDone }
            - { name: kernel.event_listener, event: payment.order.load, method: onPaymentLoad }
            - { name: kernel.event_listener, event: payment.order.success, method: onPaymentSuccess }
            - { name: kernel.event_listener, event: payment.order.fail, method: onPaymentFail }
`


## Note

- **Currency**: All payment platforms will use your cart currency to pay. You have to use [currency code](http://en.wikipedia.org/wiki/ISO_4217) following [ISO 4217 standard](http://www.iso.org/iso/home/standards/currency_codes.htm).
Every payment method will implement his internal code conversion if needed.