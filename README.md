BankWireBundle - PayFony
=====

[![Payment Suite](https://raw.github.com/mmoreram/PaymentCoreBundle/gh-pages/public/images/payment-suite.png)](https://github.com/mmoreram/PaymentCoreBundle)
[![Payment Suite](https://raw.github.com/mmoreram/PaymentCoreBundle/gh-pages/public/images/still-maintained.png)]()
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/BankwireBundle/badges/quality-score.png?s=f5ae2404f5f37bf187dece44f2cc19a0b2f774d2)](https://scrutinizer-ci.com/g/mmoreram/BankwireBundle/)

Table of contents
-----

1.  [Installing Payment Environment](https://gist.github.com/mmoreram/6771947#file-configure-payfony-environment-md)
2.  [Installing BankWireBundle](https://gist.github.com/mmoreram/6771869#file-install-platform-md)
3.  [Contribute](http://github.com/mmoreram/PaymentCoreBundle/blob/master/Resources/docs/contribute.md)
4.  [Configuration](#configuration)
5.  [Router](#router)

Configuration
-----

Configure the BankWireBundle configuration in your `config.yml`

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

Router
-----

DineroMail allows developer to specify the route of controller where dineromail payment is processed.  Also POST callback route is configured through configuration specification.  
The bundle routes must be parsed by the framework, so these lines must be included into routing.yml file  

    bankwire_payment_routes:
        resource: .
        type: bankwire

DineroMail payment button must point to route `bankwire_execute` without any parameter
