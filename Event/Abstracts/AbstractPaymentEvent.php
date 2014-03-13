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

namespace PaymentSuite\PaymentCoreBundle\Event\Abstracts;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Abstract payment event
 */
abstract class AbstractPaymentEvent extends Event
{

    /**
     * @var PaymentBridgeInterface
     *
     * Payment Bridge
     */
    private $paymentBridge;

    /**
     * @var PaymentMethodInterface
     *
     * Payment method object
     */
    private $paymentMethod;

    /**
     * Construct method
     *
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param PaymentMethodInterface $paymentMethod Payment method
     */
    public function __construct(PaymentBridgeInterface $paymentBridge, PaymentMethodInterface $paymentMethod)
    {
        $this->paymentBridge = $paymentBridge;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Get Order Wrapper
     *
     * @return PaymentBridgeInterface Payment Bridge
     */
    public function getPaymentBridge()
    {
        return $this->paymentBridge;
    }

    /**
     * Get Payment Method
     *
     * @return PaymentMethod Payment method
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}
