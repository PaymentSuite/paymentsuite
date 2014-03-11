Configuration
=============

About PaymentBridgeBundle
-------------------------

As Payment Suite should be compatible with all E-commerces projects,
it’s built without any kind of attachment with your model, so you must
build (just once) a specific bridge bundle to tell Payment Suite where
to find some data.

For this purpose, `create a Bundle`_ named PaymentBridgeBundle with the
following features.

.. _create a Bundle: http://symfony.com/doc/current/bundles/SensioGeneratorBundle/commands/generate_bundle.html

PaymentBridge Service
---------------------

Payment Service is a `service`_ that has to be necessarily implemented.
This service **must** implement
``Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface``.

.. code:: php

    <?php

    namespace YourProjectName\PaymentCoreBundle\Services\interfaces;

    use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

    class PaymentBridge implements PaymentBridgeInterface
    {
        /**
         * @var Object
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
         * Get the currency in which the order is paid
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
         * Get total order amount in cents
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
    }

This service **must** be named ``payment.bridge`` and configured in the
``Resources\config\services.yml`` file:

.. code:: yml

    services:
        # ...
        payment.bridge:
            class: YourProjectName\PaymentBridgeBundle\Services\PaymentBridge

.. _service: http://symfony.com/doc/current/book/service_container.html

Payment Event Listener
----------------------

You can `create an Event Listener`_ to subscribe to Payment process
events.

In fact, this will be the way to manage your cart and your order in
every payment stage.

.. code:: php

    <?php

    namespace YourProjectName\PaymentBridgeBundle\EventListener;

    use Mmoreram\PaymentCoreBundle\Event\PaymentOrderLoadEvent;
    use Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent;
    use Mmoreram\PaymentCoreBundle\Event\PaymentOrderDoneEvent;
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
         * On payment order load event
         *
         * @param PaymentOrderLoadEvent $paymentOrderLoadEvent Payment Order Load event
         */
        public function onPaymentOrderLoad(PaymentOrderLoadEvent $paymentOrderLoadEvent)
        {
            /*
             * Your code for this event
             */
        }

        /**
         * On payment order created event
         *
         * @param PaymentOrderCreatedEvent $paymentOrderCreatedEvent Payment Order Created event
         */
        public function onPaymentOrderCreated(PaymentOrderCreatedEvent $paymentOrderCreatedEvent)
        {
            /*
             * Your code for this event
             */
        }

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
    }

Register these event listeners in your ``Resources\config\services.yml``
file:

.. code:: yml

    services:
        # ...
        payment.event.listener:
            class:     YourProjectName\PaymentBridgeBundle\EventListener\Payment
            arguments: [@doctrine.orm.entity_manager, @mailer]
            tags:
                - { name: kernel.event_listener, event: payment.order.done, method: onPaymentOrderDone }
                - { name: kernel.event_listener, event: payment.order.created, method: onPaymentOrderCreated }
                - { name: kernel.event_listener, event: payment.order.load, method: onPaymentLoad }
                - { name: kernel.event_listener, event: payment.order.success, method: onPaymentSuccess }
                - { name: kernel.event_listener, event: payment.order.fail, method: onPaymentFail }

.. _create an Event Listener: http://symfony.com/doc/current/cookbook/service_container/event_listener.html

