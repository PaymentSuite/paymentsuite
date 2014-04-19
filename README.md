Authorize.Net (AIM) - Payment Suite
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/219aca29-4716-49f1-93f3-484d75b6a772/mini.png)](https://insight.sensiolabs.com/projects/219aca29-4716-49f1-93f3-484d75b6a772)
[![Build Status](https://travis-ci.org/PaymentSuite/AuthorizenetBundle.png?branch=1.0.1)](https://travis-ci.org/PaymentSuite/AuthorizenetBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/PaymentSuite/AuthorizenetBundle/badges/quality-score.png?s=bce86cb317aa4b4363c8a04b6a4e69556c30f5c7)](https://scrutinizer-ci.com/g/PaymentSuite/AuthorizenetBundle/)
[![Latest Stable Version](https://poser.pugx.org/paymentsuite/authorizenet-bundle/v/stable.png)](https://packagist.org/packages/paymentsuite/authorizenet-bundle)
[![Latest Unstable Version](https://poser.pugx.org/paymentsuite/authorizenet-bundle/v/unstable.png)](https://packagist.org/packages/paymentsuite/authorizenet-bundle)
[![Total Downloads](https://poser.pugx.org/paymentsuite/authorizenet-bundle/downloads.png)](https://packagist.org/packages/paymentsuite/authorizenet-bundle)

This bundle bring you a possibility to make simple payments through
[Authorize.Net](http://www.authorize.net/) using the
[PaymentSuite](https://github.com/PaymentSuite/PaymentCoreBundle) for Symfony2.

Table of contents
-----

1. [Configure Payment Environment](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Configure-Payment-Environment)
1. [Configure AuthorizenetBundle](#https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Installing-Payment-Platforms)
1. [Contribute](https://github.com/PaymentSuite/PaymentCoreBundle/wiki/Contribute)
1. [Configuration](#configuration)
1. [Router](#router)
1. [Display](#display)
1. [Customize](#customize)
1. [Testing and more documentation](#testing-and-more-documentation)

Configuration
-----

Configure the AuthorizenetBundle parameters in your `config.yml`.

``` yaml
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
```

About Authorizenet `login_id` and `tran_key` you can learn more in 
[Authorizenet documentation page](http://support.authorize.net/authkb/index?page=content&id=A576&actp=LIST_POPULAR).

Router
-----

AuthorizenetBundle allows developer to specify the route of controller where
authorizenet payment is processed.  
By default, this value is `/payment/authorizenet/execute` but this value can be
changed in configuration file.  
Anyway AuthorizenetBundle's routes must be parsed by the framework, so these
lines must be included into `routing.yml` file.

``` yaml
authorizenet_payment_routes:
   resource: .
   type: authorizenet
```

Display
-----

Once your AuthorizenetBundle is installed and well configured, you need to place
your payment form.

AuthorizenetBundle gives you all form view as requested by the payment module.

``` jinja
{% block content %}
   <div class="payment-wrapper">
      {{ authorizenet_render() }}
   </div>
{% endblock content %}
```


Customize
-----

`authorizenet_render()` just print a basic form.

As every project need its own form design, you can overwrite default form 
located in: `app/Resources/AuthorizenetBundle/views/Authorizenet/view.html.twig`.


Testing and more documentation
-----

For testing you can use these example
[these examples](http://developer.authorize.net/testingfaqs/).  
More detail about Authorizenet API you can find in this
[web](http://developer.authorize.net/).


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/PaymentSuite/authorizenetbundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

