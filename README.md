Google Wallet - Payment Suite
=====

About GoogleWalletBundle
-----

This bundle bring you a possibility to make simple payments through
[Google Wallet](http://www.google.com/wallet/). GoogleWalletBundle is payment method for Symfony2
Payment Suite and it's built following
[PaymentCore](https://github.com/PaymentSuite/PaymentCoreBundle) specifications.
PaymentCore brings for developers easy way to implement several payment methods.

Table of contents
-----

1. [Install Payment Environment](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Configure-Payment-Environment)
2. [Configuration](#configuration)
3. [Router](#router)
4. [Display](#display)
5. [Customize](#customize)
6. [Testing and more Documentation](#testing-and-more-documentation)
7. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
8. [Creating PlatformBundle](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Crating-payment-Platforms)


Configuration
-----

Configure the GoogleWalletBundle parameters in your `config.yml`.

``` yml
google_wallet:

    # google wallet keys
    merchant_id: XXXXXXXXXXXX
    secret_key: XXXXXXXXXXXX

    # Configuration for payment success redirection
    #
    # Route defines which route will redirect if payment success
    # If order_append is true, Bundle will append cart identifier into route
    #    taking order_append_field value as parameter name and
    #    PaymentOrderWrapper->getOrderId() value
    payment_success:
        route: cart_thanks
        order_append: true
        order_append_field: order_id

    # Configuration for payment fail redirection
    #
    # Route defines which route will redirect if payment fails
    # If cart_append is true, Bundle will append cart identifier into route
    #    taking cart_append_field value as parameter name and
    #    PaymentCartWrapper->getCartId() value
    payment_fail:
        route: cart_view
        cart_append: false
        cart_append_field: cart_id
```

To get `merchant_id` and `secret_key` you have to register for [Sandbox Settings](https://sandbox.google.com/checkout/inapp/merchant/settings.html) or [Production Settings](https://checkout.google.com/inapp/merchant/settings.html). Also there you have to set postback URL (must be on public DNS and not localhost). For more information you can visit page of [Google Wallet APIs](https://developers.google.com/wallet/).

Router
-----

GoogleWalletBundle allows developer to specify the route of controller where google wallet callback is processed.
By default, this value is `/payment/googlewallet/callback` but this value can be changed in configuration file.
Anyway GoogleWalletBundle's routes must be parsed by the framework, so these lines must be included into `routing.yml` file.

``` yml
google_wallet_payment_routes:
    resource: .
    type: googlewallet
```

Display
-----

Once your GoogleWalletBundle is installed and well configured, you need to place submit button which open Google Wallet pop-up.

GoogleWalletBundle gives you all code as requested by the payment module.

``` twig
{% block content %}
    <div class="payment-wrapper">
        {{ googlewallet_render() }}
    </div>
{% endblock content %}

{% block foot_script %}
    {{ parent() }}
    {{ googlewallet_scripts() }}
{% endblock foot_script %}
```


Customize
-----


As every project need its own form design, you can overwrite default button located in: `app/Resources/GoogleWalletBundle/views/GoogleWallet/view.html.twig`.


Testing and more documentation
-----

For testing, you just have to use sandbox settings.
More details about Google Wallet API you can find in this [web](https://developers.google.com/wallet/).
