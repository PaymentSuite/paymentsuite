Create Payment Method
-----

1. [Payment Method](#payment-method)
2. [Payment Event Dispatcher](#payment-event-dispatcher)
3. [Events](#events)
    * [Payment Ready Event](#payment-ready-event)
    * [Payment Done Event](#payment-done-event)
    * [Payment Success Event](#payment-success-event)
    * [Payment Fail Event](#payment-fail-event)
    * [Payment Order Created Event](#payment-order-created-event)

## Payment Method

For basic methods of PaymentMethod class you have to implement `Mmoreram\PaymentCoreBundle\PaymentMethodInterface`. Each payment method will be able implement more own methods to communicate with API of payments processor.

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


        /**
             * Return  payment amount
             *
             * @return string
             */
            public function getCurrency();
    }

## Payment Event Dispatcher

Each platform need specific part to be in charge about some logic of process. PaymentEventDispatcher is public dispatcher of payment events. As all events extend from same class, they will return the same object.

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
> La responsabilidad del core del E-commerces debería ser inyectar el nuevo Order al servicio `payment.order.wrapper`, para que tanto la plataforma de pago como los futuros eventos contengan ya el Order creado y puedan acceder a sus datos compartidos.

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


## More documentation will coming soon