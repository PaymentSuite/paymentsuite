BankWireBundle - PaymentCoreBundle
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/71cee758-4734-43a9-96ca-5ca4429eed1c/mini.png)](https://insight.sensiolabs.com/projects/71cee758-4734-43a9-96ca-5ca4429eed1c)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/PaymentSuite/BankwireBundle/badges/quality-score.png?s=48caeef36a98e5aeefbf4f52ef173d5dcac74583)](https://scrutinizer-ci.com/g/PaymentSuite/BankwireBundle/)
[![Latest Stable Version](https://poser.pugx.org/PaymentSuite/bankwire-bundle/v/stable.png)](https://packagist.org/packages/PaymentSuite/bankwire-bundle)
[![Latest Unstable Version](https://poser.pugx.org/PaymentSuite/bankwire-bundle/v/unstable.png)](https://packagist.org/packages/PaymentSuite/bankwire-bundle)
[![Total Downloads](https://poser.pugx.org/PaymentSuite/bankwire-bundle/downloads.png)](https://packagist.org/packages/PaymentSuite/bankwire-bundle)

Table of contents
-----

1. [Configure Payment Environment](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Configure-Payment-Environment)
1. [Configure AuthorizenetBundle](#https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Installing-Payment-Platforms)
1. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
1. [Configuration](#configuration)
1. [Router](#router)

Configuration
-----

Configure the BankWireBundle configuration in your `config.yml`

``` yml
bankwire:

    # By default, controller route is /payment/bankwire/execute
    controller_route: /my/custom/route

    # Configuration for payment success redirection
    #
    # Route defines which route will redirect after order creation
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

DineroMail allows developer to specify the route of controller where dineromail payment is processed.  Also POST callback route is configured through configuration specification.  
The bundle routes must be parsed by the framework, so these lines must be included into routing.yml file  

``` yml
bankwire_payment_routes:
    resource: .
    type: bankwire
```

Bankwire payment button must point to route `bankwire_execute` without any parameter


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/PaymentSuite/bankwirebundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

