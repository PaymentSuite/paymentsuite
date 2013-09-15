[![Payment Suite](http://origin-shields-io.herokuapp.com/payment/suite.png?color=yellow)](https://github.com/mmoreram/PaymentCoreBundle)  [![Payment Suite](http://origin-shields-io.herokuapp.com/Still/maintained.png?color=green)]()  [![Build Status](https://travis-ci.org/mmoreram/PaymentCoreBundle.png?branch=master)](https://travis-ci.org/mmoreram/PaymentCoreBundle)  [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/PaymentCoreBundle/badges/quality-score.png?s=0be5ab01885ab241a3b5a871dbc1164c5bcb75b2)](https://scrutinizer-ci.com/g/mmoreram/PaymentCoreBundle/)

> Info. This Bundle is currently in progress and tested.  
> If you are interested in using this bundle, please star it and will recieve last notices.  
> All help will be very grateful.  
> I am at your disposal.  
>   
> [@mmoreram](https://github.com/mmoreram)

Payment Suite for Symfony
-----

Payment Suite para Symfony2 es un conjunto de herramientas para unificar todas las plataformas de pago en un solo modelo de eventos.  

Se basa en un sistema de clases abstractas y interfaces para que sea lo mas fácil posible añadir nuevas plataformas sin que el usuario final tenga que añadir lógica extra en su ecommerce.  

Table of contents
-----
1. [About Payment Suite](#about-payment-suite)
2. [Available Payment Platforms](#available-payment-platforms)
3. [PaymentBridgeBundle](#paymentbridgebundle)
    * [Cart Wrapper](#cart-wrapper)
    * [Currency](#currency)
    * [Order Wrapper](#order-wrapper)
    * [Payment Method](#payment-method)
    * [Payment Event Dispatcher](#payment-event-dispatcher)
4. [Events](#events)
    * [Payment Ready Event](#payment-ready-event)
    * [Payment Done Event](#payment-done-event)
    * [Payment Success Event](#payment-success-event)
    * [Payment Fail Event](#payment-fail-event)
    * [Payment Order Created Event](#payment-order-created-event)
5. [Platforms](#platforms)
    * [Paypal Bundle](#paypal-bundle)
    * [Paymill Bundle](#paymill-bundle)
    * [Sermepa Bundle](#sermepa-bundle)
6. [Contribute](#contribute)


# About Payment Suite

The Symfony2 Payment Suite is just a way to implement any payment platform for Symfony2 based Ecommerces, with a common structure. Your project will simply need to listen to a few events, so the method of payment will be fully transparent.

    + Payment platforms
    - Headaches
    = Code
    + Time

The philosophy that leads us to do this project is simply no need to repeat in every project the same lines of code ( yes, we love OpenSource ). We want every ecommerce based in Symfony2 to meet us, to join us and to contribute with new platforms.

This project belongs to everyone, for everyone. Take a look at [Contribute](#contribute] point.

# Available Payment Platforms

## [PaymentCoreBundle](https://github.com/mmoreram/PaymentCoreBundle)

* Scrutinizer - [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/PaymentCoreBundle/badges/quality-score.png?s=0be5ab01885ab241a3b5a871dbc1164c5bcb75b2)](https://scrutinizer-ci.com/g/mmoreram/PaymentCoreBundle/)
* Travis-CI - [![Build Status](https://travis-ci.org/mmoreram/PaymentCoreBundle.png?branch=master)](https://travis-ci.org/mmoreram/PaymentCoreBundle)
* Packagist - [https://packagist.org/packages/mmoreram/payment-core-bundle](https://packagist.org/packages/mmoreram/payment-core-bundle)
* KnpBundles - 

## [PaymillBundle](https://github.com/mmoreram/PaymillBundle) - [Paymill](https://www.paymill.com)

* Scrutinizer - [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/PaymillBundle/badges/quality-score.png?s=561838fdedd54e5d4c05036b8ef46b0bca4b3c48)](https://scrutinizer-ci.com/g/mmoreram/PaymillBundle/)
* Travis-CI - [![Build Status](https://travis-ci.org/mmoreram/PaymillBundle.png?branch=master)](https://travis-ci.org/mmoreram/PaymillBundle)
* Packagist - [https://packagist.org/packages/mmoreram/paymill-bundle](https://packagist.org/packages/mmoreram/paymill-bundle)
* KnpBundles - 

## Being Developed

* PaypalBundle
* PayUBundle
* SermepaBundle
* Transference
* Stripe


# PaymentBridgeBundle

As Payment Suite should be compatible with all ecommerce projects, is built without any kind of acoplation with your model, so you must build ( just once ) a specific bridge bundle to tell Payment Suite where to find some data.

To do this, create a Bundle named PaymentBridgeBundle with some specific classes.

## Cart Wrapper

One of services this bundle must implement is the Cart Wrapper. This services **must** be named `payment.cart.wrapper`, and **must** implements `Mmoreram\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface`.

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

All payment platforms will use your cart currency to pay. Code `ISO 4217` will be used for currencies as specified [here](http://www.iso.org/iso/home/standards/currency_codes.htm).  
Every payment platform will implement its own internal code conversion if needed.

## Order Wrapper

The other service this bundle must implement is the Order Wrapper. This services **must** be named `payment.order.wrapper`, and **must** implements `Mmoreram\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface`.


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

## Payment Method

Cada una de las plataformas debe identificarse de forma individual, así como pasar una serie de datos específicas para que el proyecto tenga acceso a los datos de pagos. Esta clase debe implementar `Mmoreram\PaymentCoreBundle\PaymentMethodInterface` aunque puede contener tantos datos internos como permita el método de pago. De esta forma, si la propia plataforma implementa eventos propios y se requiere acceso a ciertos datos específicos, será posible.

    <!php

    namespace Mmoreram\PaymentCoreBundle;


    /**
     * Interface for all type of payments
     */
    interface PaymentMethodInterface
    {

        /**
         * Return type of payment name
         *
         * @return string
         */
        public function getPaymentName();


        /**
         * Return type of payment name
         *
         * @return string
         */
        public function getAmount();
    }

## Payment Event Dispatcher

Toda plataforma necesita un core de proceso especíco encargado de toda la lógica de negocio. Este debe ser el encargado de lanzar todos los eventos disponibles del core. Es para esto que PaymentCore dispone de un servicio público específico para hacer dispatch de algunos eventos.  

Por definición, toda plataforma debería, en algún momento u otro lanzar todos y cada uno de los eventos, por si algun subscriber necesita realizar alguna operación relacionada con tal evento.  

Cada uno de los eventos recibe un objeto event distinto, aunque todos ellos extienden de un abstracto común, por lo que en realidad, todos tienen disponibles los mismos objetos.

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

# Events

## Payment Ready Event

    /**
     * This event is thrown when a payment is ready to be processed
     * 
     * event.name : payment.ready
     * event.class : PaymentReadyEvent
     * 
     */
    const PAYMENT_READY = 'payment.ready';

> En este momento, getOrderWrapper debería devolver un wrapper con un order `null` ya que tan solo el cart está construido y available.
> Una posible utilidad podría ser la creación inmediato de un Order en un estado "ready_to_pay".

## Payment Done Event

    /**
     * This event is thrown when a payment is paid, no matter the result
     *
     * event.name : payment.done
     * event.class : PaymentDoneEvent
     */
    const PAYMENT_DONE = 'payment.done';

> Este evento se lanza siempre que un pago ha sido registrado, haya sido correcto o rejected.
> Es posible que en algunos casos, en este momento y posteriormente, tengamos ya un order creado ( por ejemplo en pagos por transferencia ), asi que en cada una de las plataformas, se tendrá que gestionar estos casos.
> Una posible utilidad podría ser el control de intentos de pago por parte del usuario, sin importar el resultado de tal acción.

## Payment Success Event

    /**
     * This event is thrown when a payment is paid succesfuly
     *
     * event.name : payment.success
     * event.class : PaymentSuccessEvent
     */
    const PAYMENT_SUCCESS = 'payment.success';

> En principio, la transacción ha sido correcta.
> Su principal utilidad es la creación de un order a partir del Cart pagado.
> La responsabilidad del core del ecommerce debería ser inyectar el nuevo Order al servicio `payment.order.wrapper`, para que tanto la plataforma de pago como los futuros eventos contengan ya el Order creado y puedan acceder a sus datos compartidos.

## Payment Fail Event

    /**
     * This event is thrown when a payment can't be paid for any reason
     *
     * event.name : payment.fail
     * event.class : PaymentFailEvent
     */
    const PAYMENT_FAIL = 'payment.fail';

## Payment Order Created Event

    /**
     * This event is thrown when a payment can't be paid for any reason
     *
     * event.name : payment.order.created
     * event.class : PaymentOrderCreatedEvent
     */
    const PAYMENT_ORDER_CREATED = 'payment.order.created';

> En este punto, el servicio `payment.order.wrapper` debería contener una referencia real al order generado por el sistema
> Una posible utilidad podría ser el log de toda Order creada, relacionando en base de datos, el identificador de este con el método de pago aplicado.

Contribute
-----

All code is Symfony2 Code formatted, so every pull request must validate phpcs standards.  
You should read [Symfony2 coding standards](http://symfony.com/doc/current/contributing/code/standards.html) and install [this](https://github.com/opensky/Symfony2-coding-standard) CodeSniffer to check all code is validated.  

There is also a policy for contributing to this project. All pull request must be all explained step by step, to make us more understandable and easier to merge pull request. All new features must be tested with PHPUnit.