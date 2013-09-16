Install PaymentCoreBundle
-----

1. [About Payment Bridge Bundle](#about-payment-bridge-bundle)
2. [Cart Wrapper](#cart-wrapper)
3. [Currency](#currency)
4. [Order Wrapper](#order-wrapper)
5. [Payment Event Listener](#payment-event-listener)


# About PaymentBridgeBundle

As Payment Suite should be compatible with all E-commerces projects, it's built without any kind of attachment with your model, so you must build (just once) a specific bridge bundle to tell Payment Suite where to find some data.

To do this, create a Bundle named PaymentBridgeBundle with next classes.

## Cart Wrapper

Cart Wrapper is one of necessary services to implement. This services **must** be named `payment.cart.wrapper`, and **must** implements `Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface`.

    <?php

    namespace Mmoreram\PaymentCoreBundle\Services\interfaces;

    /**
     * Interface for CartWrapper
     */
    interface CartWrapperInterface
    {

        /**
         * Return current cart amount.
         *
         * This is an interface.
         * Each project must implement this interface with current customer cart
         *
         * @return float Cart amount
         */
        public function getAmount();


        /**
         * Get cart
         *
         * @return Object Cart object
         */
        public function getCart();


        /**
         * Return order description
         *
         * @return string
         */
        public function getCartDescription();


        /**
         * Return cart id
         *
         * @return integer
         */
        public function getCartId();


        /**
         * Return cart currency
         *
         * @return string Currency
         */
        public function getCurrency();
    }

## Currency

All payment platforms will use your cart currency to pay. You have to use [currency code](http://en.wikipedia.org/wiki/ISO_4217) following [ISO 4217 standard](http://www.iso.org/iso/home/standards/currency_codes.htm).
Every payment method will implement his internal code conversion if needed.

## Order Wrapper

Another necessary service to implement is Order Wrapper. This services **must** be named `payment.order.wrapper`, and **must** implements `Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface`.


    <?php

    namespace Mmoreram\PaymentCoreBundle\Services\interfaces;

    /**
     * Interface for OrderWrapper
     */
    interface OrderWrapperInterface
    {

        /**
         * Set order to OrderWrapper
         *
         * @var Object $order Order element
         */
        public function setOrder($order);


        /**
         * Get order
         *
         * @return Object Order object
         */
        public function getOrder();


        /**
         * Get order given an identifier
         *
         * @param integer $orderId Order identifier, usually defined as primary key or unique key
         *
         * @return Object Order object
         */
        public function findOrder($orderId);


        /**
         * Return order description
         *
         * @return string
         */
        public function getOrderDescription();


        /**
         * Return order identifier value
         *
         * @return integer
         */
        public function getOrderId();
    }


## Payment Event Listener

You can create Event listener to subscribe to Payment process events.  

In fact, this will be the way to manage your cart and your order in every payment stage.