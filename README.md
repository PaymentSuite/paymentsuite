PaypalExpressCheckout - Payment Suite
=====


Table of contents
-----

1.  [Installing Payment Environment](https://gist.github.com/paymentsuite/6771947#file-configure-payfony-environment-md)
2.  [Installing PaypalExpressCheckout](https://gist.github.com/paymentsuite/6771869#file-install-platform-md)
3.  [Contribute](https://gist.github.com/paymentsuite/6813203#file-contribute-payfony-md)
4.  [Configuration](#configuration)
5.  [Extra Data](#extra-data)
6.  [Router](#router)
7.  [Display](#display)
8.  [Customize](#customize)


Configuration
-----

Configure the PaypalExpressCheckout configuration in your `config.yml`

``` yml
paypal_checkout:

    # Api definition
    username: XXXXXXXXXX
    password: XXXXXXXXXX
    signature: XXXXXXXXXX
    debug: true|false

    # By default, controller route is /payment/paypal_checkout/execute
    controller_route: /my/custom/route

    # Configuration for payment success redirection
    #
    # Route defines which route will redirect if payment successes
    # If order_append is true, Bundle will append card identifier into route
    #    taking order_append_field value as parameter name and
    #    PaymentOrderWrapper->getOrderId() value
    payment_success:
        route: payment_success
        order_append: true
        order_append_field: order_id

    # Configuration for payment fail redirection
    #
    # Route defines which route will redirect if payment fails

    payment_fail:
        route: payment_failed
        order_append: false
        order_append_field: order_id
```

Extra Data
-----

PaymentBridge Service must return, at least, these fields.

* order_description

Router
-----

PaypalExpressCheckout allows developer to specify the route of controller where paymill
payment is processed.
By default, this value is `/payment/paypal/execute` but this value can be
changed in configuration file.
Anyway, the bundle routes must be parsed by the framework, so these lines must
be included into routing.yml file.

``` yml
paypal_payment_routes:
    resource: .
    type: paypal_express_checkout
```

Display
-----

Once your Paypal Express Checkout is installed and well configured, you need to place your
payment form.

PaypalExpressCheckout gives you all form view as requested by the payment module.

``` jinja
{% block content %}
    <div class="payment-wrapper">
        {{ paypal_express_checkout_render() }}
    </div>
{% endblock content %}
```

Customize
-----

`paypal_render()` only print form in a simple way.

As every project need its own form design, you should overwrite in
`app/Resources/PaypalExpressCheckoutBundle/views/PaypalExpressCheckout/view.html.twig`, paypal form render
template placed in
`PaymentSuite/PaypalExpressCheckoutBundle/Resources/views/PaypalExpressCheckout/view.html.twig`.


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/PaymentSuite/paypalexpresscheckoutbundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

