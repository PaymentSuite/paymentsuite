<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
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
