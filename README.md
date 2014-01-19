Authorize.Net (AIM) - Payment Suite
=====

[![Build Status](https://travis-ci.org/PaymentSuite/StripeBundle.png?branch=master)](https://travis-ci.org/PaymentSuite/AuthorizenetBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/dpcat237/AuthorizenetBundle/badges/quality-score.png?s=43290e459683b8b94de1e695ca851a6451ab1b50)](https://scrutinizer-ci.com/g/dpcat237/AuthorizenetBundle/)
[![Latest Stable Version](https://poser.pugx.org/dpcat237/authorizenet-bundle/v/stable.png)](https://packagist.org/packages/dpcat237/authorizenet-bundle)
[![Latest Unstable Version](https://poser.pugx.org/dpcat237/authorizenet-bundle/v/unstable.png)](https://packagist.org/packages/dpcat237/authorizenet-bundle)
[![Total Downloads](https://poser.pugx.org/dpcat237/authorizenet-bundle/downloads.png)](https://packagist.org/packages/dpcat237/authorizenet-bundle)

About AuthorizenetBundle
-----

This bundle bring you a possibility to make simple payments through
[Authorize.Net](http://www.authorize.net/). AuthorizenetBundle is payment method for Symfony2
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
6. [Testing and more documentation](#testing-and-more-documentation)
7. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
8. [Creating PlatformBundle](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Crating-payment-Platforms) 


Configuration
-----

Configure the AuthorizenetBundle parameters in your `config.yml`.

    authorizenet:

        # authorizenet keys
        login_id: XXXXXXXXXXXX
        tran_key: XXXXXXXXXXXX
        test_mode: true

        # By default, controller route is /payment/authorizenet/execute
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

About Authorizenet `login_id` and `tran_key` you can learn more in [Authorizenet documentation page](http://support.authorize.net/authkb/index?page=content&id=A576&actp=LIST_POPULAR).

Router
-----

AuthorizenetBundle allows developer to specify the route of controller where authorizenet payment is processed.  
By default, this value is `/payment/authorizenet/execute` but this value can be changed in configuration file.  
Anyway AuthorizenetBundle's routes must be parsed by the framework, so these lines must be included into `routing.yml` file.

    authorizenet_payment_routes:
        resource: .
        type: authorizenet

Display
-----

Once your AuthorizenetBundle is installed and well configured, you need to place your payment form.

AuthorizenetBundle gives you all form view as requested by the payment module.

    {% block content %}

            <div class="payment-wrapper">

                {{ authorizenet_render() }}

            </div>

    {% endblock content %}


Customize
-----

`authorizenet_render()` just print a basic form.

As every project need its own form design, you can overwrite default form located in: `app/Resources/AuthorizenetBundle/views/Authorizenet/view.html.twig`.


Testing and more documentation
-----

For testing you can use these example [these examples](http://developer.authorize.net/testingfaqs/).
More detail about Authorizenet API you can find in this [web](http://developer.authorize.net/).
