PaypalBundle - PayFony
=====


Table of contents
-----

1.  [Installing Payment Environment](https://gist.github.com/mmoreram/6771947#file-configure-payfony-environment-md)
2.  [Installing PaypalBundle](https://gist.github.com/mmoreram/6771869#file-install-platform-md)
3.  [Contribute](https://gist.github.com/mmoreram/6813203#file-contribute-payfony-md)
4.  [Configuration](#configuration)
5.  [Extra Data](#extra-data)
6.  [Router](#router)
7.  [Display](#display)
8.  [Customize](#customize)


Configuration
-----

Configure the PaypalBundle configuration in your `config.yml`

    paypal:

        rest_api:
            client_id: XXXXXXXXXX
            secret: XXXXXXXXXX
        http:
            connection_timeout: 30
            retry: 1
        service:
            mode: sandbox|live
        log:
            enabled: true|false
            filename: XXXXXXXXXX
            log_level: FINE|INFO|WARN|ERROR

        # By default, controller route is /payment/paypal/execute
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

* order_description

Router
-----

PaypalBundle allows developer to specify the route of controller where paymill payment is processed.
By default, this value is `/payment/paypal/execute` but this value can be changed in configuration file.
Anyway, the bundle routes must be parsed by the framework, so these lines must be included into routing.yml file  

    paypal_payment_routes:
        resource: .
        type: paypal

Display
-----

Once your Paypal is installed and well configured, you need to place your payment form.

PaypalBundle gives you all form view as requested by the payment module.

    {% block content %}

            <div class="payment-wrapper">

                {{ paypal_render() }}

            </div>

    {% endblock content %}

    {% block foot_script %}

        {{ parent() }}

        {{ paypal_scripts() }}

    {% endblock foot_script %}

Customize
-----

`paypal_render()` only print form in a simple way.  

As every project need its own form design, you should overwrite in `app/Resources/PaymillBundle/views/Paypal/view.html.twig`, paypal form render template placed in `Mandrieu/Paypal/Bundle/Resources/views/Paypal/view.html.twig`.
