FreePayment - PaymentCoreBundle
=====

Table of contents
-----

1. [Install Payment Environment](https://github.com/mmoreram/PaymentCoreBundle/wiki/Configure-Payment-Environment)
2. [Creating FreePaymentBundle](https://github.com/mmoreram/PaymentCoreBundle/wiki/Crating-payment-Platforms)
3. [Configuration](#configuration)
4. [Router](#router)
5. [Contribute](https://github.com/mmoreram/PaymentCoreBundle/wiki/Contribute)


Configuration
-----

Configure the FreePaymentBundle configuration in your `config.yml`

``` yml
freepayment:

    # By default, controller route is /payment/freepayment/execute
    controller_route: /my/custom/route

    # Configuration for payment success redirection
    #
    # Route defines which route will redirect if payment successes
    # If order_append is true, Bundle will append card identifier into route
    #    taking order_append_field value as parameter name and
    #    PaymentOrderWrapper->getOrderId() value
    payment_success:
        route: card_thanks
        order_append: true
        order_append_field: order_id
```

Router
-----

FreePaymentBundle allows developer to specify the route of controller where paymill payment is processed.
By default, this value is `/payment/paymill/execute` but this value can be changed in configuration file.
Anyway, the bundle routes must be parsed by the framework, so these lines must be included into routing.yml file

``` yml
freepayment_payment_routes:
    resource: .
    type: freepayment
```