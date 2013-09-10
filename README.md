[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/befactory/PaymillBundle/badges/quality-score.png?s=5235ee24261574e120cd0644ae11ec7bf87a624f)](https://scrutinizer-ci.com/g/befactory/PaymillBundle/)

> Info. This Bundle is currently in progress and tested.  
> If you are interested in using this bundle, please star it and will recieve last notices.  
> All help will be very grateful.  
> I am at your disposal.  
>   
> [@mmoreram](https://github.com/mmoreram)


Paymill Platform in Payment Suite for Symfony
-----

Plataforma de Paymill para la Suite de Pago de Symfony. Toda la definición de eventos está ubicada en la documentación de [PaymentCoreBundle](https://github.com/mmoreram/PaymentCoreBundle), así como todo lo necesario para trabajar con la suite de pago.

Table of contents
-----

1.  [Installing PaymillBundle](#installing-paymillbundle)
2.  [Configuration](#configuration)
3.  [Display](#display)
4.  [Customize](#customize)


# Installing [PaymillBundle](https://github.com/mmoreram/PaymillBundle)

You have to add require line into you composer.json file

    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.3.*",
        ...
        "mmoreram/paymill-bundle": "dev-master"
    },

Then you have to use composer to update your project dependencies

    php composer.phar update

And register the bundle in your appkernel.php file

    return array(
        // ...
        new Mmoreram\PaymentCoreBundle\PaymentCoreBundle(),
        new Mmoreram\PaymillBundle\PaymillBundle(),
        // ...
    );

# Configuration

Configure the PaymillBundle configuration in your `config.yml`

    paymill:

        # paymill keys
        public_key: XXXXXXXXXXXX
        private_key: XXXXXXXXXXXX

        # By default, controller route is /payment/paymill/execute
        controller_route: /my/custom/route

        # Configuration for payment success redirection
        #
        # Route defines which route will redirect if payment successes
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

# Display

Once your Paymill is installed and well configured, you need to place your payment form.  

PaymillBundle gives you all form view as requested by the payment module.

    {% block content %}

            <div class="payment-wrapper">

                {{ paymill_render() }}

            </div>

    {% endblock content %}

    {% block foot_script %}

        {{ parent() }}

        {{ paymill_scripts() }}

    {% endblock foot_script %}

# Customize

`paymill_render()` only print form in a simple way.  

As every project need its own form design, you should overwrite in `app/Resources/PaymillBundle/views/Paymill/view.html.twig`, paymill form render template placed in `Mmoreram/Paymill/Bundle/Resources/views/Paymill/view.html.twig`.
