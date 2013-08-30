#Payment Suite for Symfony

##For developers
Si eres desarrollador y quieres implementar tu propio método de pago, solo tienes que conocer las herramients que te brinda el PaymentCore. 
PaymentCore ofrece todo un esqueleto de clases abstractas y interfaces para que puedas implementar fácilmente tu plataforma. 

###PaymentBridge
Como es lógico este bundle está completamente desacoplado de cualquier proyecto que lo utilize. Esto significa que en algun momento hay que implementar un Bundle de tipo Bridge, el cual definirá una serie de servicios necesarios para que Cualquier plataforma de pago pueda tener acceso a ciertos datos de tu modelo.  
Es por esto que cuando utilizemos cualquier plataforma de pago implementado en esta suite, debemos crear un bundle llamado "PaymentBridgeBundle" que defina tan solo dos servicios

####payment.cart.wrapper
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

####payment.order.wrapper
    <?php

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


###Eventos
####payment.ready

Se lanza previo pago. La razón de este evento es para que las plataformas que disponen de prepago ( sistemas de pago en tienda ) puedan transformar su cart en order sin haber ejecutado el pago. Cualquier subscriber que esté suscrito a este evento debería crear un Order en un estado ready, pero no pagado.
####payment.done

Se lanza cuando el pago ha sido ejecutado. Este evento se lanza sea cual sea el resultado del pago.
####payment.fail

Se lanza cuando el pago ha sido ejecutado con un resultado negativo.
####payment.success

Se lanza cuandl el pago ha sido ejecutado con un resultado positivo.
####payment.order.created

Se lanza cuando un nuevo order ha sido creado. La utilidad de este evento es que todos los subscribers a este evento podrán tratar el Order creado







##For 