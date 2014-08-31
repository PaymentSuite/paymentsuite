<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaymentCoreBundle\Event\Abstracts;

use Symfony\Component\EventDispatcher\Event;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

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
