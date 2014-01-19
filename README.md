Stripe - Payment Suite
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c580f420-08a7-49f3-a55f-b834aabad113/mini.png)](https://insight.sensiolabs.com/projects/c580f420-08a7-49f3-a55f-b834aabad113)
[![Build Status](https://travis-ci.org/PaymentSuite/StripeBundle.png?branch=master)](https://travis-ci.org/PaymentSuite/StripeBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/PaymentSuite/StripeBundle/badges/quality-score.png?s=4d9dff8533c0f853d67949d6ce9b348a85bf5437)](https://scrutinizer-ci.com/g/PaymentSuite/StripeBundle/)
[![Latest Stable Version](https://poser.pugx.org/dpcat237/stripe-bundle/v/stable.png)](https://packagist.org/packages/dpcat237/stripe-bundle)
[![Latest Unstable Version](https://poser.pugx.org/dpcat237/stripe-bundle/v/unstable.png)](https://packagist.org/packages/dpcat237/stripe-bundle)
[![Total Downloads](https://poser.pugx.org/dpcat237/stripe-bundle/downloads.png)](https://packagist.org/packages/dpcat237/stripe-bundle)

About Stripe Bundle
-----

This bundle bring you a possibility to make simple payments through
[Stripe](https://stripe.com). StripeBundle is payment method for Symfony2
Payment Suite and it's built following
[PaymentCore](https://github.com/PaymentSuite/PaymentCoreBundle) specifications.
PaymentCore brings for developers easy way to implement several payment methods.

Table of contents
-----

1. [Install Payment Environment](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Configure-Payment-Environment)
2. [Install StripeBundle](#install)
3. [Configuration](#configuration)
4. [Router](#router)
5. [Display](#display)
6. [Customize](#customize)
7. [Testing and more Documentation](#testing-and-more-documentation)
8. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
9. [Creating PlatformBundle](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Crating-payment-Platforms)

## Install

You have to add require line into you composer.json file

``` yml
"require": {
   // ...
   "dpcat237/stripe-bundle": "1.0.1"
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
   new dpcat237\StripeBundle\StripeBundle(),
);
```

Configuration
-----

Configure the StripeBundle parameters in your `config.yml`.

``` yml
stripe:

    # stripe keys
    public_key: XXXXXXXXXXXX
    private_key: XXXXXXXXXXXX

    # By default, controller route is /payment/stripe/execute
    controller_route: /my/custom/route

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

About Stripe `public_key` and `private_key` you can learn more in [Stripe documentation page](https://stripe.com/docs/tutorials/dashboard#api-keys)

Router
-----

StripeBundle allows developer to specify the route of controller where stripe payment is processed.
By default, this value is `/payment/stripe/execute` but this value can be changed in configuration file.
Anyway StripeBundle's routes must be parsed by the framework, so these lines must be included into `routing.yml` file.

``` yml
stripe_payment_routes:
    resource: .
    type: stripe
```

Display
-----

Once your StripeBundle is installed and well configured, you need to place your payment form.

StripeBundle gives you all form view as requested by the payment module.

``` twig
{% block content %}
    <div class="payment-wrapper">
        {{ stripe_render() }}
    </div>
{% endblock content %}

{% block foot_script %}
    {{ parent() }}
    {{ stripe_scripts() }}
{% endblock foot_script %}
```


Customize
-----

`stripe_render()` just print a basic form.

As every project need its own form design, you can overwrite default form located in: `app/Resources/StripeBundle/views/Stripe/view.html.twig` following [Stripe documentation](https://stripe.com/docs/tutorials/forms).

In another hand, Stripe [recommend](https://stripe.com/docs/tutorials/forms#create-a-single-use-token) use [jQuery form validator](https://github.com/stripe/jquery.payment).


Testing and more documentation
-----

For testing you can use [these examples](https://stripe.com/docs/testing).
More detail about Stripe API you can find in this [web](https://stripe.com/docs/api/php).
