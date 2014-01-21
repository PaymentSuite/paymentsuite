Free Payment - Payment Suite
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f7e00e6f-1e43-46f8-9161-64c000a421d9/mini.png)](https://insight.sensiolabs.com/projects/f7e00e6f-1e43-46f8-9161-64c000a421d9)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/PaymentSuite/FreePaymentBundle/badges/quality-score.png?s=962c277666ad58ac942c8180fc72ffee76b45d6c)](https://scrutinizer-ci.com/g/PaymentSuite/FreePaymentBundle/)
[![Latest Stable Version](https://poser.pugx.org/paymentsuite/free-payment-bundle/v/stable.png)](https://packagist.org/packages/paymentsuite/free-payment-bundle)
[![Latest Unstable Version](https://poser.pugx.org/paymentsuite/free-payment-bundle/v/unstable.png)](https://packagist.org/packages/paymentsuite/free-payment-bundle)
[![Total Downloads](https://poser.pugx.org/paymentsuite/free-payment-bundle/downloads.png)](https://packagist.org/packages/paymentsuite/free-payment-bundle)

Table of contents
-----

1. [Install Payment Environment](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Configure-Payment-Environment)
1. [Creating PlatformBundle](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Crating-payment-Platforms)
1. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
1. [Configuration](#configuration)
1. [Router](#router)


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

FreePaymentBundle allows developer to specify the route of controller where freepayment payment is processed.
By default, this value is `/payment/freepayment/execute` but this value can be changed in configuration file.
Anyway, the bundle routes must be parsed by the framework, so these lines must be included into routing.yml file

``` yml
freepayment_payment_routes:
    resource: .
    type: freepayment
```
