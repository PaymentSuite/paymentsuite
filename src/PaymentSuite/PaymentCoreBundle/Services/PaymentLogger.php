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

namespace PaymentSuite\PaymentCoreBundle\Services;

use Psr\Log\LoggerInterface;

/**
 * Payment logger
 */
class PaymentLogger
{
    /**
     * @var LoggerInterface
     *
     * Logger
     */
    protected $logger;

    /**
     * @var bool
     *
     * active
     */
    protected $active;

    /**
     * @var string
     *
     * level
     */
    protected $level;

    /**
     * @var string
     *
     * paymentBundle
     */
    protected $paymentBundle;

    /**
     * Construct method
     *
     * @param LoggerInterface $logger Logger
     * @param bool            $active Do log or not
     * @param string          $level  Log level
     */
    public function __construct(LoggerInterface $logger, $active, $level)
    {
        $this->logger = $logger;
        $this->active = $active;
        $this->level = $level;
    }

    /**
     * Log payment message, prepending payment bundle name if set
     *
     * @param string $message Message to log
     * @param array  $context Context
     *
     * @return PaymentLogger Self object
     */
    public function log($message, array $context = array())
    {
        if ($this->active) {
            if ($this->paymentBundle) {
                $message = '[' . $this->paymentBundle . '] ' . $message;
            }
            $this->logger->log($this->level, $message, $context);
        }

        return $this;
    }

    /**
     * Sets PaymentBundle
     *
     * @param string $paymentBundle PaymentBundle
     *
     * @return PaymentLogger Self object
     */
    public function setPaymentBundle($paymentBundle)
    {
        $this->paymentBundle = $paymentBundle;

        return $this;
    }

    /**
     * Get PaymentBundle
     *
     * @return string PaymentBundle
     */
    public function getPaymentBundle()
    {
        return $this->paymentBundle;
    }

    /**
     * Sets Level
     *
     * @param string $level Level
     *
     * @return PaymentLogger Self object
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get Level
     *
     * @return string Level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Sets Active
     *
     * @param boolean $active Active
     *
     * @return PaymentLogger Self object
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get Active
     *
     * @return boolean Active
     */
    public function getActive()
    {
        return $this->active;
    }
}
