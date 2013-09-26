DineroMail Platform for Symfony Payment Suite
-----

[![Payment Suite](http://mmoreram.github.io/PaymentCoreBundle/public/images/payment-suite.png)](https://github.com/mmoreram/PaymentCoreBundle)  [![Payment Suite](http://mmoreram.github.io/PaymentCoreBundle/public/images/still-maintained.png)]()  

> Info. This Bundle is currently in progress and tested.  
> If you are interested in using this bundle, please star it and will recieve last notices.  
> All help will be very grateful.  
> I am at your disposal.  
>   
> [@mmoreram](https://github.com/mmoreram)

Table of contents
-----

1.  [About DineroMail Bundle](#about-dineromail-bundle)
2.  [Installing Payment Environment](#installing-payment-environment)
3.  [Installing DineroMail Bundle](#installing-dineromail-bundle)
8.  [Contribute](http://github.com/mmoreram/PaymentCoreBundle/blob/master/Resources/docs/contribute.md)

About DineroMail Bundle
=====

Implementation of DineroMail payment method for Chile for Symfony2 Payment Suite. Is built following PaymentCore specifications and working with defined events

Installing Payment Environment
=====

DineroMailBundle works using an standard, defined in PaymentCoreBundle. You will find [here](http://github.com/mmoreram/PaymentCoreBundle) everything about how to configure your environment to work with this suite

Installing [DineroMail Bundle](https://github.com/mmoreram/DineroMailBundle)
=====

You have to add require line into you composer.json file

    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.3.*",
        ...
        "mmoreram/dineromail-bundle": "dev-master"
    },

Then you have to use composer to update your project dependencies

    php composer.phar update

And register the bundle in your appkernel.php file

    return array(
        // ...
        new Mmoreram\PaymentCoreBundle\PaymentCoreBundle(),
        new Mmoreram\DineroMailBundle\DineroMailBundle(),
        // ...
    );
