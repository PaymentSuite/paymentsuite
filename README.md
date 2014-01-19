Google Wallet - Payment Suite
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/00ce64e6-62b4-49f3-9f23-cdcaa04c43f9/mini.png)](https://insight.sensiolabs.com/projects/00ce64e6-62b4-49f3-9f23-cdcaa04c43f9)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/PaymentSuite/GoogleWalletBundle/badges/quality-score.png?s=a90b67126880c247e0b520e4fa0c50c1101ae55a)](https://scrutinizer-ci.com/g/PaymentSuite/GoogleWalletBundle/)
[![Latest Stable Version](https://poser.pugx.org/dpcat237/google-wallet-bundle/v/stable.png)](https://packagist.org/packages/dpcat237/google-wallet-bundle)
[![Latest Unstable Version](https://poser.pugx.org/dpcat237/google-wallet-bundle/v/unstable.png)](https://packagist.org/packages/dpcat237/google-wallet-bundle)
[![Total Downloads](https://poser.pugx.org/dpcat237/google-wallet-bundle/downloads.png)](https://packagist.org/packages/dpcat237/google-wallet-bundle)

About GoogleWalletBundle
-----

This bundle bring you a possibility to make simple payments through
[Google Wallet](http://www.google.com/wallet/). GoogleWalletBundle is payment method for Symfony2
Payment Suite and it's built following
[PaymentCore](https://github.com/PaymentSuite/PaymentCoreBundle) specifications.
PaymentCore brings for developers easy way to implement several payment methods.

Table of contents
-----

1. [Install GoogleWalletBundle](#install)
2. [Configure Payment Environment](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Configure-Payment-Environment)
3. [Configure GoogleWalletBundle](#configure-googlewalletbundle)
4. [Extra Data](#extra-data)
5. [Router](#router)
6. [Display](#display)
7. [Customize](#customize)
8. [Testing and more Documentation](#testing-and-more-documentation)
9. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
10. [Creating PlatformBundle](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Crating-payment-Platforms)

## Install

You have to add require line into you composer.json file

``` yml
"require": {
   // ...
   "dpcat237/google-wallet-bundle": "1.0.1"
}
```

Then you have to use composer to update your project dependencies

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar update
```

And register the bundle in your appkernel.php file

``` php
return array(
   // ...
   new PaymentSuite\PaymentCoreBundle\PaymentCoreBundle(),
   new dpcat237\StripeBundle\GoogleWalletBundle(),
);
```

Configure GoogleWalletBundle
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

Extra Data
-----

PaymentBridge Service must return, at least, these fields.

* order_name
* order_description

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
