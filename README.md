[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/befactory/PaymentCoreBundle/badges/quality-score.png?s=daabfb5e5091a3adddb4b48bd5ebe55a7a0bbf56)](https://scrutinizer-ci.com/g/befactory/PaymentCoreBundle/)

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
2. [PaymentBridgeBundle](#paymentbridgebundle)
    * [Cart Wrapper](#cart-wrapper)
    * [Order Wrapper](#order-wrapper)
    * [Payment Method](#payment-method)
    * [Payment Event Dispatcher](#payment-event-dispatcher)
3. [Events](#events)
    * [Payment Ready Event](#payment-ready-event)
    * [Payment Done Event](#payment-done-event)
    * [Payment Success Event](#payment-success-event)
    * [Payment Fail Event](#payment-fail-event)
    * [Payment Order Created Event](#payment-order-created-event)
4. [Platforms](#platforms)
    * [Paypal Bundle](#paypal-bundle)
    * [Paymill Bundle](#paymill-bundle)
    * [Sermepa Bundle](#sermepa-bundle)


# About Payment Suite

La Suite de Payment mantiene desde el primer momento un índice de acoplamiento completamente nulo con el ecommerce, ya que es completamente transparente al modelo y la implementación de este. Esto hace que sea un código muy testeable unitariamente y muy fácil de entender a nivel abstracto.  

De todas formas, tenemos que tener en cuenta que de alguna forma u otra, cualquier plataforma de pago tiene que tener acceso a algunos datos del modelo del ecommerce, todos relacionados con el Cart o el Order generado a través del Cart.  

# PaymentBridgeBundle

Por ello, cada ecommerce deberá crear un Bundle propio que hará de puente entre su modelo y el nivel de abstracción del Payment Suite. En este Bundle deberá, tan solo, definir dos servicios. Por nomenclatura, este bundle deberá llamarse PaymentBridgeBundle.

## Cart Wrapper

Uno de los servicios que debe implementar PaymentBridgeBundle es el que añada una capa a nuestro Cart. Su nombre **debe** ser `payment.cart.wrapper` y debe implementar a `Befactory\PaymentCoreBundle\Services\Interfaces\CartWrapperInterface`.  

    <?php

    namespace Befactory\PaymentCoreBundle\Services\interfaces;

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
    }

## Order Wrapper

El otro servicio es exactamente el mismo pero para acceder a ciertos valores referentes a Order. Su nombre **debe** ser `payment.order.wrapper` y debe implementar a `Befactory\PaymentCoreBundle\Services\Interfaces\OrderWrapperInterface`.  

    <?php

    namespace Befactory\PaymentCoreBundle\Services\interfaces;

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

Cada una de las plataformas debe identificarse de forma individual, así como pasar una serie de datos específicas para que el proyecto tenga acceso a los datos de pagos. Esta clase debe implementar `Befactory\PaymentCoreBundle\PaymentMethodInterface` aunque puede contener tantos datos internos como permita el método de pago. De esta forma, si la propia plataforma implementa eventos propios y se requiere acceso a ciertos datos específicos, será posible.

    <!php

    namespace Befactory\PaymentCoreBundle;


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

# Platforms