Developing Platform
===================

| Since any payment platform is implemented on the existing Payment
Suite for Symfony2 is something like a plugin, must be implemented
simply those specific features of the platform itself.
| The core provides a number of tools, both definition and execution, so
it is not too complex to implement each of the platforms, and providing
homogeneity in the set of all events regarding concerns.

PaymentMethod
-------------

The first class that must implement either integrated platform is called
PaymentMethod. This must extend an interface located in
``Mmoreram\PaymentCoreBundle\PaymentMethodInterface``, so you should
just implement a single method.

.. code:: php

    <?php

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

| At the time that our platform offers data on the response of the
payment, it is interesting that this class implements their getters,
although not common on all platforms. This is done because there may be
a case where a project wants to subscribe to an event of Core, acting
only if the payment is one in specific. In this case, you will have
access to the data offered without any problem.
| Here is an example of what could be a kind of a new payment method
called AcmePaymentBundle

.. code:: php

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

Configuration
-------------

| Consider the data coming through PaymentBridge service defined by the
project, and you should not redefine them statically. The configuration
data is used for completely static definition.
| A clear example of configuration is

-  Public and private keys
-  API url
-  Controllers routes
-  Static data, like logo

This configuration must be properly defined and validated, as defined
`here`_. Let’s see a configuration sample

.. code:: yml

    services:

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

    It is important to understand the motivation of configuration items.
    You only have to define elements unchanged at project level and
    environment-level writable. Pay dependent elements are placed along
    PaymentBridge as we will see later.

When the configuration settings are validated by the bundle, the
platform should add, one by one, as parameters. Please check that all
changed as a parameter fields always have the same format. Here is a
short example of what could be a configuration validator.

.. _here: http://symfony.com/doc/current/components/config/definition.html

.. code:: php

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

And an example of parametrization of configuration items. Each platform
must implement their own items.

.. code:: php

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

Extra data
----------

All configuration of the payment must be collected by the method of
``getExtraData`` of ``PaymentBridge`` service. This method will provide
all the necessary values for all installed platforms, so that each
platform must, specifically, validate that the required fields are
present in the method response array.

Controllers
-----------

All controller that requires payment platform itself, must be associated
with a dynamically generated path. Its motivation is that the user must
be able to define each of the paths associated with each of the actions
of the drivers. For this, each platform must make available to the user
the possibility to overwrite the path as follows.

.. code:: php

    <?php

    namespace Mmoreram\AcmeBundle\DependencyInjection;

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
            $rootNode = $treeBuilder->root('acme');

            $rootNode
                ->children()
                    ...

                    ->scalarNode('controller_route')
                        ->defaultValue('/payment/acme/execute')
                    ->end()

                    ...
                ->end();

            return $treeBuilder;
        }
    }

Once we provide the possibility to define this variable, adding one by
default (should follow this pattern), we transform the variable
parameter configuration, in order to inject.

.. code:: php

    <?php

    namespace Mmoreram\AcmeBundle\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\HttpKernel\DependencyInjection\Extension;
    use Symfony\Component\DependencyInjection\Loader;

    /**
     * This is the class that loads and manages your bundle configuration
     */
    class DineromailExtension extends Extension
    {
        /**
         * {@inheritDoc}
         */
        public function load(array $configs, ContainerBuilder $container)
        {
            $configuration = new Configuration();
            $config = $this->processConfiguration($configuration, $configs);
            $container->setParameter('acme.controller.route', $config['controller_route']);
        }
    }

Services
--------

All services with responsibility for launching events PaymentCore, MUST
inject an instance of
``Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher``. This
class is responsible for providing direct methods to launch the kernel
events. All methods require paymentBridge and paymentmethod.

.. code:: php

    /**
     * At this point, order must be created given a card, and placed in PaymentBridge
     *
     * So, $this->paymentBridge->getOrder() must return an object
     */
    $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

Exceptions
----------

PaymentCore provides a number of Exceptions to be used by the platforms.
It is important to unify certain behaviors using transparently payment
platform.

PaymentAmountsNotMatchException
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This exception must be thrown when the value of the payment goes through
form, is validated and is not equal to the real value of the payment.

PaymentOrderNotFoundException
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Launched the first event of the kernel, as explained in `Order load
event`_, PaymentBridge order must have a private variable in ``order``.
This implies that the ``getOrder()`` should return an object. This
exception must be thrown if this method returns ``null``.

PaymentExtraDataFieldNotDefinedException
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

As explained in `Extra Data fields`_ may have platforms that require
extra fields. You can throw this exception if one of the camps is not
found and is required.

PaymentException
~~~~~~~~~~~~~~~~

Any exceptions regarding payment methods ``PaymentException`` extends so
you can try a transparent any exception concerning PaymentCore.

.. _Order load event: #order-load
.. _Extra Data fields: #extra-data

Kernel Events
-------------

Order load
~~~~~~~~~~

| This event recieves as paramater an instance of
``Mmoreram\PaymentCoreBundle\Event\PaymentOrderLoadEvent`` with thow
methods.
| ``$event->getPaymentBridge`` returns the implementation of
``PaymentBridgeInterface`` needed by PaymentCore.
| ``$event->getPaymentMethod`` returns the implementation of
``PaymentMethodInterface`` implemented by Method Platform.

.. code:: yml

    services:
        my_event_listener:
            class: AcmeBundle\EventListener\MyEventListener
            tags:
              - { name: kernel.event_listener, event: payment.order.load, method: onOrderLoad }

Order created
~~~~~~~~~~~~~

| This event recieves as paramater an instance of
``Mmoreram\PaymentCoreBundle\Event\PaymentOrderCreatedEvent`` with thow
methods.
| ``$event->getPaymentBridge`` returns the implementation of
``PaymentBridgeInterface`` needed by PaymentCore.
| ``$event->getPaymentMethod`` returns the implementation of
``PaymentMethodInterface`` implemented by Method Platform.

.. code:: yml

    services:
        my_event_listener:
            class: AcmeBundle\EventListener\MyEventListener
            tags:
              - { name: kernel.event_listener, event: payment.order.created, method: onOrderCreated }

Order done
~~~~~~~~~~

| This event recieves as paramater an instance of
``Mmoreram\PaymentCoreBundle\Event\PaymentOrderDone`` with thow methods.
| ``$event->getPaymentBridge`` returns the implementation of
``PaymentBridgeInterface`` needed by PaymentCore.
| ``$event->getPaymentMethod`` returns the implementation of
``PaymentMethodInterface`` implemented by Method Platform.

.. code:: yml

    services:
        my_event_listener:
            class: AcmeBundle\EventListener\MyEventListener
            tags:
              - { name: kernel.event_listener, event: payment.order.load, method: onOrderDone }

Order success
~~~~~~~~~~~~~

| This event recieves as paramater an instance of
``Mmoreram\PaymentCoreBundle\Event\PaymentOrderSuccessEvent`` with thow
methods.
| ``$event->getPaymentBridge`` returns the implementation of
``PaymentBridgeInterface`` needed by PaymentCore.
| ``$event->getPaymentMethod`` returns the implementation of
``PaymentMethodInterface`` implemented by Method Platform.

.. code:: yml

    services:
        my_event_listener:
            class: AcmeBundle\EventListener\MyEventListener
            tags:
              - { name: kernel.event_listener, event: payment.order.load, method: onOrderSuccess }

Order fail
~~~~~~~~~~

| This event recieves as paramater an instance of
``Mmoreram\PaymentCoreBundle\Event\PaymentOrderFailEvent`` with thow
methods.
| ``$event->getPaymentBridge`` returns the implementation of
``PaymentBridgeInterface`` needed by PaymentCore.
| ``$event->getPaymentMethod`` returns the implementation of
``PaymentMethodInterface`` implemented by Method Platform.

.. code:: yml

    services:
        my_event_listener:
            class: AcmeBundle\EventListener\MyEventListener
            tags:
              - { name: kernel.event_listener, event: payment.order.load, method: onOrderFail}

