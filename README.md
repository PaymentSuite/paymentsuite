DineroMailBundle - PayFony
=====

[![Payment Suite](http://mmoreram.github.io/PaymentCoreBundle/public/images/payment-suite.png)](https://github.com/mmoreram/PaymentCoreBundle)
[![Payment Suite](http://mmoreram.github.io/PaymentCoreBundle/public/images/still-maintained.png)]()
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/DineroMailBundle/badges/quality-score.png?s=b56ed7f3a43fd7543777ec9beac26a30891fcf43)](https://scrutinizer-ci.com/g/mmoreram/DineroMailBundle/)

Table of contents
-----

1.  [Installing Payment Environment](https://gist.github.com/mmoreram/6771947#file-configure-payfony-environment-md)
2.  [Installing PaymillBundle](https://gist.github.com/mmoreram/6771869#file-install-platform-md)
3.  [Contribute](http://github.com/mmoreram/PaymentCoreBundle/blob/master/Resources/docs/contribute.md)
4.  [Configuration](#configuration)
5.  [Extra Data](#extra-data)
6.  [Router](#router)

Configuration
-----

Configure the PaymillBundle configuration in your `config.yml`

    paymill:

        # some specific payment cnfig
        merchant: XXXXXXXXXX
        country: es
        seller_name: MyProject
        header_image: http://my.image/path.jpg
        url_redirect_enabled: true

        # payment methods
        payment_methods_available:
            - cl_visa
            - cl_magna
            - cl_presto

        # By default, controller route is /payment/paymill/execute
        controller_route: /my/custom/route

        # Controller process route, by default /payment/dineromail/process/{id_order}
        controller_process_route: /my/process/route/{id_order}

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

        # Configuration for payment fail redirection
        #
        # Route defines which route will redirect if payment fails
        # If card_append is true, Bundle will append card identifier into route
        #    taking card_append_field value as parameter name and
        #    PaymentCardWrapper->getCardId() value
        payment_fail:
            route: card_view
            card_append: false
            card_append_field: card_id

Extra Data
-----

PaymentBridge Service must return, at least, these fields.

* customer_firstname
* customer_lastname
* customer_email
* customer_phone
* language

Router
-----

DineroMail allows developer to specify the route of controller where dineromail payment is processed.  Also POST callback route is configured through configuration specification.  
The bundle routes must be parsed by the framework, so these lines must be included into routing.yml file  

    dineromail_payment_routes:
        resource: .
        type: dineromail

DineroMail payment button must point to route `dineromail_execute` without any parameter