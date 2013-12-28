<?php

/**
 * PaypalBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalBundle
 *
 * Mickael Andrieu 2013
 */

namespace Mmoreram\PaymillBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaypalBundle\Services\Wrapper\PaypalTransactionWrapper;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Mmoreram\PaymillBundle\PaypalMethod;;

/**
 * Paypal manager
 */
class PaypalManager
{

}