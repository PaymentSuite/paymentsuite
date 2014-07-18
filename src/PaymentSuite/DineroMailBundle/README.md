DineroMailBundle - PayFony
=====

[![Payment Suite](https://raw.github.com/paymentsuite/PaymentCoreBundle/gh-pages/public/images/payment-suite.png)](https://github.com/paymentsuite/PaymentCoreBundle)
[![Payment Suite](https://raw.github.com/paymentsuite/PaymentCoreBundle/gh-pages/public/images/still-maintained.png)]()
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/paymentsuite/DineroMailBundle/badges/quality-score.png?s=b56ed7f3a43fd7543777ec9beac26a30891fcf43)](https://scrutinizer-ci.com/g/paymentsuite/DineroMailBundle/)

Table of contents
-----

1.  [Installing Payment Environment](https://gist.github.com/paymentsuite/6771947#file-configure-payfony-environment-md)
2.  [Installing DineroMail](https://gist.github.com/paymentsuite/6771869#file-install-platform-md)
3.  [Contribute](https://gist.github.com/paymentsuite/6813203#file-contribute-payfony-md)
4.  [Configuration](#configuration)
5.  [Extra Data](#extra-data)
6.  [Router](#router)

Configuration
-----

Configure the DineroMail configuration in your `config.yml`

    dineromail:

        # some specific payment config
        merchant: XXXXXXXXXX
        ipn_key: XXXXXXXXXX
        country: 1 # 1 => Argentina, 3 => Chile
        seller_name: MyProject
        header_image: http://my.image/path.jpg
        url_redirect_enabled: true

        # payment methods
        payment_methods_available:
            - cl_visa
            - cl_magna
            - cl_presto

        # By default, controller route is /payment/dineromail/execute
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


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/PaymentSuite/dineromailbundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

