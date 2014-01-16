Authorize.Net (AIM) - Payment Suite
=====

[![Payment Suite](https://raw.github.com/mmoreram/PaymentCoreBundle/gh-pages/public/images/payment-suite.png)](https://github.com/mmoreram/PaymentCoreBundle)
[![Payment Suite](https://raw.github.com/mmoreram/PaymentCoreBundle/gh-pages/public/images/still-maintained.png)]()
[![Build Status](https://api.travis-ci.org/dpcat237/AuthorizenetBundle.png?branch=master)](https://travis-ci.org/dpcat237/AuthorizenetBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/dpcat237/AuthorizenetBundle/badges/quality-score.png?s=43290e459683b8b94de1e695ca851a6451ab1b50)](https://scrutinizer-ci.com/g/dpcat237/AuthorizenetBundle/)

> This Bundle is under development but already functional and partially tested.
> Any comment, suggestion or contribution will be very appreciated.
>
> [@dpcat237](https://github.com/dpcat237)



Table of contents
-----

1.  [Installing Payment Environment](https://gist.github.com/mmoreram/6771947#file-configure-payfony-environment-md)
2.  [Installing PaymillBundle](https://gist.github.com/mmoreram/6771869#file-install-platform-md)
3.  [Contribute](https://gist.github.com/mmoreram/6813203#file-contribute-payfony-md)
4.  [Configuration](#configuration)
5.  [Router](#router)
6.  [Display](#display)
7.  [Customize](#customize)
8.  [Testing and more documentation](#testing-and-more-documentation)


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
