Paymill - Payment Suite
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6547cf38-372e-40c5-98bd-1b1491ed8606/mini.png)](https://insight.sensiolabs.com/projects/6547cf38-372e-40c5-98bd-1b1491ed8606)
[![Build Status](https://travis-ci.org/PaymentSuite/PaymillBundle.png?branch=master)](https://travis-ci.org/PaymentSuite/PaymillBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/PaymentSuite/PaymillBundle/badges/quality-score.png?s=a320b312d0c4e0f1842221f20d6261eb27c43618)](https://scrutinizer-ci.com/g/PaymentSuite/PaymillBundle/)
[![Latest Stable Version](https://poser.pugx.org/paymentsuite/paymill-bundle/v/stable.png)](https://packagist.org/packages/paymentsuite/paymill-bundle)
[![Latest Unstable Version](https://poser.pugx.org/paymentsuite/paymill-bundle/v/unstable.png)](https://packagist.org/packages/paymentsuite/paymill-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/52c05da5ec13758efc0002c4/badge.png)](https://www.versioneye.com/user/projects/52c05da5ec13758efc0002c4)
[![Total Downloads](https://poser.pugx.org/paymentsuite/paymill-bundle/downloads.png)](https://packagist.org/packages/paymentsuite/paymill-bundle)

Table of contents
-----

1. [Install Payment Environment](https://github.com/mmoreram/PaymentCoreBundle/wiki/Configure-Payment-Environment)
2. [Creating PaymillBundle](https://github.com/mmoreram/PaymentCoreBundle/wiki/Crating-payment-Platforms)
3. [Configuration](#configuration)
4. [Extra Data](#extra-data)
5. [Router](#router)
6. [Display](#display)
7. [Customize](#customize)
8. [Contribute](https://github.com/mmoreram/PaymentCoreBundle/wiki/Contribute)


Configuration
-----

Configure the PaymillBundle configuration in your `config.yml`

``` yml
paymill:

    # paymill keys
    public_key: XXXXXXXXXXXX
    private_key: XXXXXXXXXXXX

    # By default, controller route is /payment/paymill/execute
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
```

Extra Data
-----

PaymentBridge Service must return, at least, these fields.

* order_description

Router
-----

PaymillBundle allows developer to specify the route of controller where paymill
payment is processed.
By default, this value is `/payment/paymill/execute` but this value can be
changed in configuration file.
Anyway, the bundle routes must be parsed by the framework, so these lines must
be included into routing.yml file

``` yml
paymill_payment_routes:
    resource: .
    type: paymill
```

Display
-----

Once your Paymill is installed and well configured, you need to place your
payment form.

PaymillBundle gives you all form view as requested by the payment module.

``` twig
{% block content %}
        <div class="payment-wrapper">
            {{ paymill_render() }}
        </div>
{% endblock content %}

{% block foot_script %}
    {{ parent() }}
    {{ paymill_scripts() }}
{% endblock foot_script %}
```

Customize
-----

`paymill_render()` only print form in a simple way.

As every project need its own form design, you should overwrite in
`app/Resources/PaymillBundle/views/Paymill/view.html.twig`, paymill form render
template placed in
`PaymentSuite/Paymill/Bundle/Resources/views/Paymill/view.html.twig`.
