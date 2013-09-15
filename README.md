Stripe Platform for Symfony Payment Suite
-----

[![Payment Suite](http://origin-shields-io.herokuapp.com/payment/suite.png?color=yellow)](https://github.com/mmoreram/PaymentCoreBundle)  [![Payment Suite](http://origin-shields-io.herokuapp.com/Still/maintained.png?color=green)]()  [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/dpcat237/StripeBundle/badges/quality-score.png?s=10dab38a47f5ca4c11a2de2e4f1237555c5e8660)](https://scrutinizer-ci.com/g/dpcat237/StripeBundle/)

> This Bundle is under development but already functional and partially tested.
> Any comment, suggestion or contribution will be very appreciated.
>
> [@dpcat237](https://github.com/dpcat237)

Table of contents
-----

1.  [About Stripe Bundle](#about-payment-suite)
2.  [Installing StripeBundle](#installing-stripebundle)
3.  [Configuration](#configuration)
4.  [Router](#router)
5.  [Display](#display)
6.  [Customize](#customize)
7.  [Contribute](#contribute)

# About Stripe Bundle

This bundle bring you a possibility to make simple payments through [Stripe](https://stripe.com). StripeBundle is payment method for Symfony2 Payment Suite and it's built following [PaymentCore](https://github.com/mmoreram/PaymillBundle) specifications.

# Installing [StripeBundle](https://github.com/dpcat237/StripeBundle)

You have to add next line into you composer.json file

    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.3.*",
        ...
        "dpcat237/stripe-bundle": "dev-master"
    },

Then you have to use composer to update your project dependencies

    php composer.phar update

And register StripeBundle in your `AppKernel.php` file

    return array(
        // ...
        new Mmoreram\PaymentCoreBundle\PaymentCoreBundle(),
        new dpcat237\StripeBundle\StripeBundle(),
        // ...
    );

# Configuration

Configure the StripeBundle parameters in your `config.yml`

    stripe:

        # [stripe keys](https://stripe.com/docs/tutorials/dashboard#api-keys)
        public_key: XXXXXXXXXXXX
        private_key: XXXXXXXXXXXX

        # By default, controller route is /payment/stripe/execute
        controller_route: /my/custom/route

        # Currency value. By default EUR (using [ISO 4217 standard](http://en.wikipedia.org/wiki/ISO_4217)).
        currency: EUR

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

# Router

StripeBundle allows developer to specify the route of controller where stripe payment is processed.  
By default, this value is `/payment/stripe/execute` but this value can be changed in configuration file.  
Anyway StripeBundle's routes must be parsed by the framework, so these lines must be included into routing.yml file

    stripe_payment_routes:
        resource: .
        type: stripe

# Display

Once your StripeBundle is installed and well configured, you need to place your payment form.

StripeBundle gives you all form view as requested by the payment module.

    {% block content %}

            <div class="payment-wrapper">

                {{ stripe_render() }}

            </div>

    {% endblock content %}

    {% block foot_script %}

        {{ parent() }}

        {{ stripe_scripts() }}

    {% endblock foot_script %}


# Customize

`stripe_render()` just print a basic form.

As every project need its own form design, you can overwrite default form located in `app/Resources/StripeBundle/views/Stripe/view.html.twig`.


Contribute
-----

All code is Symfony2 Code formatted, so every pull request must be validated with [phpcs standards](http://symfony.com/doc/current/contributing/code/standards.html) which you can install [following these steps](https://github.com/opensky/Symfony2-coding-standard).

There is also a policy for contributing to this project. All pull request must be all explained step by step, to make for contributors more understandable and easier to merge the pull request. All new features must be tested with [PHPUnit](http://symfony.com/doc/current/book/testing.html).