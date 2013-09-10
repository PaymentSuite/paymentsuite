<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymentCoreBundle\Event;

use Mmoreram\PaymentCoreBundle\Event\Abstracts\AbstractPaymentEvent;

/**
 * Event for payment failed
 */
class PaymentFailEvent extends AbstractPaymentEvent
{

}