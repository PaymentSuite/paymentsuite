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

namespace PaymentSuite\PayUBundle\Model\Abstracts;

/**
 * Abstract Model class for result models
 */
abstract class PayuResult
{
    /**
     * @var mixed
     *
     * payload
     */
    protected $payload;

    /**
     * Sets Payload
     *
     * @param mixed $payload Payload
     *
     * @return PayuResult Self object
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get Payload
     *
     * @return mixed Payload
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
