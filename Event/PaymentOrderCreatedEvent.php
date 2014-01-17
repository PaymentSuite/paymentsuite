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

namespace PaymentSuite\PaymentCoreBundle\Event;

use PaymentSuite\PaymentCoreBundle\Event\Abstracts\AbstractPaymentEvent;

/**
 * Event for payment created
 */
class PaymentOrderCreatedEvent extends AbstractPaymentEvent
{

}