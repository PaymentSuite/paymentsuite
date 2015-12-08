<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaymentCoreBundle\Services;

use Psr\Log\LoggerInterface;

/**
 * Payment logger.
 */
class PaymentLogger
{
    /**
     * @var LoggerInterface
     *
     * Logger
     */
    private $logger;

    /**
     * @var bool
     *
     * active
     */
    private $active;

    /**
     * Construct method.
     *
     * @param LoggerInterface $logger Logger
     * @param bool            $active Do log or not
     */
    public function __construct(
        LoggerInterface $logger,
        $active
    ) {
        $this->logger = $logger;
        $this->active = $active;
    }

    /**
     * Log payment message, prepending payment bundle name if set.
     *
     * @param string $level         Level
     * @param string $message       Message to log
     * @param string $paymentMethod Payment method
     * @param array  $context       Context
     *
     * @return PaymentLogger Self object
     */
    public function log(
        $level,
        $message,
        $paymentMethod,
        array $context = []
    ) {
        if (!$this->active) {
            return $this;
        }

        $this
            ->logger
            ->log(
                $level,
                "[$paymentMethod] - $message",
                $context
            );

        return $this;
    }
}
