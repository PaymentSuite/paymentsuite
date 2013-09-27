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
=====

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
=====

Ten en cuenta los datos que llegan a través del servicio PaymentBridge definido por el proyecto, ya que no debes volver a definirlos de forma estática.
La configuración sirve para datos completamente estáticos, de definición.
Un ejemplo claro de configuración es

* Claves pública y privada
* Url de API
* Rutas de retorno
* y muchas mas...

Esta configuración debe estar correctamente definida y validada, tal y como se define [aqui](http://symfony.com/doc/current/components/config/definition.html).
Miremos un ejemplo de configuración

    acmepayment:
        public_key: XXXXXXXXXX
        private_key: XXXXXXXXXX
        payment_success:
            route: payment_success
            order_append: true
            order_append_field: order_id
        payment_fail:
            route: payment_failed
            order_append: false

> Es importante entender la motivación de los elementos de configuración.  
> Solo tienen que ir elementos invariables a nivel de proyecto, sobreescribibles a nivel de entorno.  
> Los elementos dependientes del Pago deberán ubicarse en PaymentBridge como veremos después.  

Cuando los valores de configuración son validados por el bundle se deben añadir, uno a uno, como parámetros. Por favor, revise que todos los campos transformados como parámetro tienen siempre el mismo formato. Aqui tenemos un pequeño ejemplo de lo que podría ser un validador de configuración.

    <?php

    /**
     * AcmePaymentBundle for Symfony2
     */

    namespace Mmoreram\AcmePaymentBundle\DependencyInjection;

    use Symfony\Component\Config\Definition\Builder\TreeBuilder;
    use Symfony\Component\Config\Definition\ConfigurationInterface;

    /**
     * This is the class that validates and merges configuration from your app/config files
     */
    class Configuration implements ConfigurationInterface
    {
        /**
         * {@inheritDoc}
         */
        public function getConfigTreeBuilder()
        {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('acmepayment');

            $rootNode
                ->children()
                    ->scalarNode('public_key')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('private_key')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->arrayNode('payment_success')
                        ->children()
                            ->scalarNode('route')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('order_append')
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('order_append_field')
                                ->defaultValue('order_id')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('payment_fail')
                        ->children()
                            ->scalarNode('route')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('order_append')
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('order_append_field')
                                ->defaultValue('card_id')
                            ->end()
                        ->end()
                    ->end()
                ->end();

            return $treeBuilder;
        }
    }

Y un ejemplo de parametrización de elementos de configuración. Cada plataforma debe implementar sus propios elementos.

    <?php

    /**
     * AcmePaymentBundle for Symfony2
     */

    namespace Mmoreram\AcmePaymentBundle\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\HttpKernel\DependencyInjection\Extension;
    use Symfony\Component\DependencyInjection\Loader;

    /**
     * This is the class that loads and manages your bundle configuration
     */
    class AcmePaymentExtension extends Extension
    {
        /**
         * {@inheritDoc}
         */
        public function load(array $configs, ContainerBuilder $container)
        {
            $configuration = new Configuration();
            $config = $this->processConfiguration($configuration, $configs);

            $container->setParameter('acmepayment.private.key', $config['private_key']);
            $container->setParameter('acmepayment.public.key', $config['public_key']);

            $container->setParameter('acmepayment.success.route', $config['payment_success']['route']);
            $container->setParameter('acmepayment.success.order.append', $config['payment_success']['order_append']);
            $container->setParameter('acmepayment.success.order.field', $config['payment_success']['order_append_field']);

            $container->setParameter('acmepayment.fail.route', $config['payment_fail']['route']);
            $container->setParameter('acmepayment.fail.order.append', $config['payment_fail']['order_append']);
            $container->setParameter('acmepayment.fail.order.field', $config['payment_fail']['order_append_field']);
        }
    }

Toda la configuración relativa al pago debe ser recogida mediante el método `getExtraData` de `PaymentBridge`. Este método proveerá todos los valores necesarios para todas las plataformas instaladas, por lo que cada plataforma deberá, de forma específica, validar que los campos requeridos están presentes en el array de respuesta de tal método.  

Esto se hace mediante el `ExtraDataValidator`, un servicio suscrito al evento `kernel.request` que se encargará básicamente de validar que los campos necesarios son definidos. Esto no valida que estos tengan valores correctos, solo que estan definidos. Veamos un ejemplo de Validador.

    <?php

    /**
     * AcmePaymentBundle for Symfony2
     */

    namespace Mmoreram\AcmePaymentBundle\Validator;

    use Mmoreram\PaymentCoreBundle\Services\Abstracts\AbstractPaymentExtraDataValidator;

    /**
     * AcmePaymentExtraDataValidator class
     */
    class AcmePaymentExtraDataValidator extends AbstractPaymentExtraDataValidator
    {

        /**
         * Return extra data fields needed for this bundle
         */
        public function getFields()
        {
            return array(

                'myfield1',
                'myfield2'
            );
        }
    }

This class must extends class `AbstractPaymentExtraDataValidator` and implements `getFields` method. This method must return always an array with required fields.  
If one or more fields are not defined, an Exception will be thrown with some descritive information about missing fields.  
As this class is extending another abstract class with its constructor, the service definition must be something like this

    acmepayment.validator.extradata:
        class: Mmoreram\AcmePaymentBundle\Validator\AcmePaymentExtraDataValidator
        arguments:
            payment.bridge: @payment.bridge
        tags:
            - { name: kernel.event_listener, event: kernel.request, method: validate }

Method `validate`, placed also in abstract class, will perform the validation of the fields.

Controladores y Rutas
=====