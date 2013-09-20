Payment Platforms from Payment Suite
=====

* Primeros Pasos - PaymentMethod
* Configuracion  y validacion
* Controladores y rutas
* fromularios
* servicios
* tests
* documentation
* nomenclature
* quality

Primeros Pasos
=====

Dado que cualquier plataforma de pago que se implementa sobre la ya existente Payment Suite para Symfony2 es algo parecida a un plugin, habrá que implementar simplemente aquellas features específicas de la plataforma en si.
El core proporciona una serie de herramientas, tanto de definición como de ejecución, para que no sea demasiado complejo la implementación de cada uno de las plataformas, y ofreciendo homogeniedad en el conjunto de todos ellos en cuanto a eventos se refiere.

PaymentMethod
-----

La primera clase que debe implementar cualquiera de las plataformas integradas es la llamada PaymentMethod. Esta deberá extender de una interface ubicada en `Mmoreram\PaymentCoreBundle\PaymentMethodInterface`, por lo que simplemente deberá implementar un solo método.

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
    }

Eso no implica que no pueda implementar ninguno mas.
En el momento que nuestra plataforma nos ofrece datos sobre la respuesta del pago, es interesante que esta clase las implemente, aunque no sean comunes en todas las plataformas. Esto se hace porque puede haber el caso en que un proyecto quiera subscribirse a un evento del Core, actuando solo en caso que el pago sea uno en específico. En este caso, tendrá acceso a los datos ofrecidos sin ningún problema.
Aqui tenemos un ejemplo de lo que podría ser un clase de un nuevo método de pago llamado AcmePaymentBundle

    <?php

    /**
     * AcmePaymentBundle for Symfony2
     */

    namespace Mmoreram\AcmePaymentBundle;

    use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


    /**
     * AcmePaymentMethod class
     */
    class AcmePaymentMethod implements PaymentMethodInterface
    {

        /**
         * @var SomeExtraData
         *
         * Some extra data given by payment response
         */
        private $someExtraData;


        /**
         * Get AcmePayment method name
         * 
         * @return string Payment name
         */
        public function getPaymentName()
        {
            return 'acme_payment';
        }


        /**
         * Set some extra data
         *
         * @param string $someExtraData Some extra data
         *
         * @return AcmePaymentMethod self Object
         */
        public function setSomeExtraData($someExtraData)
        {
            $this->someExtraData = $someExtraData;

            return $this;
        }


        /**
         * Get some extra data
         *
         * @return array Some extra data
         */
        public function getSomeExtraData()
        {
            return $someExtraData;
        }
    }

Configuración
-----

Ten en cuenta los datos que llegan a través del servicio PaymentBridge definido por el proyecto, ya que no debes volver a definirlos de forma estática.
La configuración sirve para datos completamente estáticos, de definición.
Un ejemplo claro de configuración es

* Claves pública y privada
* Url de API
* Rutas de retorno
* y muchas mas...

Esta configuración debe estar correctamente definida y validada, tal y como se define [aqui](http://symfony.com/doc/current/components/config/definition.html).
Miremos un ejemplo de configuración

    paymill:
        public_key: XXXXXXXXXX
        private_key: XXXXXXXXXX
        payment_success:
            route: payment_success
            order_append: true
            order_append_field: order_id
        payment_fail:
            route: payment_failed
            order_append: false

Cuando los valores de configuración son validados por el bundle se deben añadir, uno a uno, como parámetros. Por favor, revise 