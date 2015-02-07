Redsys - Payment Suite
=====

Configuration
-----

Configure the RedsysBundle configuration in your `config.yml`

``` yml
redsys:

    # Merchant code provided by Redsys
    merchant_code: XXXXXXXXXXXX

    # Secret Key provided by Redsys
    secret_key: XXXXXXXXXXXX

    # Url to which the payment form is sent
    url: 'https://sis-t.redsys.es:25443/sis/realizarPago'

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
        route: card_fail
        order_append: false
        order_append_field: card_id

    # Configuration for Redsys form display route
    #
    # By default, controller execute route is /payment/redsys/execute
    controller_execute_route: /my/custom/execute/route

    # Configuration for the route that Redsys will send the transaction result request to
    #
    # By default, controller route is /payment/redsys/result
    controller_result_route: /my/custom/result/route

```

Extra Data
-----

PaymentBridge Service must return, at least, these fields.

* terminal

The following fields are optional

* transaction_type
* product_description
* merchant_titular
* merchant_name

Router
-----

RedsysBundle allows developer to specify the route of controller where redsys
payment is processed as well as the route that will receive the transaction response.
By default, this values are  `/payment/redsys/execute`and `/payment/redsys/result` but this values can be
changed in configuration file.
Anyway, the bundle routes must be parsed by the framework, so these lines must
be included into routing.yml file

``` yml
redsys_payment_routes:
    resource: .
    type: redsys
```

PaymentBridge
-----

The PaymentBridge must implement `PaymentBridgeRedsysInterface`, because you need the extra parameter 'dsOrder'.
To do this you must implement the method `getOrderNumber ()`

```php
class PaymentBridge implements PaymentBridgeRedsysInterface {
    /**
     * Return dsOrder identifier value
     *
     * @return integer
     */
    public function getOrderNumber()
    {
        ...
    }
}
```