BankWireBundle - PaymentCoreBundle Suite
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f164e52e-b1bd-4344-b326-4b6be997d94d/mini.png)](https://insight.sensiolabs.com/projects/f164e52e-b1bd-4344-b326-4b6be997d94d)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/BankwireBundle/badges/quality-score.png?s=f5ae2404f5f37bf187dece44f2cc19a0b2f774d2)](https://scrutinizer-ci.com/g/mmoreram/BankwireBundle/)
[![Latest Stable Version](https://poser.pugx.org/mmoreram/bankwire-bundle/v/stable.png)](https://packagist.org/packages/mmoreram/bankwire-bundle)
[![Latest Unstable Version](https://poser.pugx.org/mmoreram/bankwire-bundle/v/unstable.png)](https://packagist.org/packages/mmoreram/bankwire-bundle)
[![Dependency Status](https://www.versioneye.com/php/mmoreram:bankwire-bundle/1.0.1/badge.png)](https://www.versioneye.com/php/mmoreram:bankwire-bundle/1.0.1)
[![Total Downloads](https://poser.pugx.org/mmoreram/bankwire-bundle/downloads.png)](https://packagist.org/packages/mmoreram/bankwire-bundle)

Table of contents
-----

1. [Install Payment Environment](https://github.com/mmoreram/PaymentCoreBundle/wiki/Configure-Payment-Environment)
2. [Creating BankWireBundle](https://github.com/mmoreram/PaymentCoreBundle/wiki/Crating-payment-Platforms)
3. [Configuration](#configuration)
4. [Router](#router)
5. [Contribute](https://github.com/mmoreram/PaymentCoreBundle/wiki/Contribute)

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
