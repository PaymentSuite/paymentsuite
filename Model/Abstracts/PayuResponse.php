<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Model\Abstracts;

use JMS\Serializer\Annotation as JMS;

/**
 * Abstract Model class for response models
 */
abstract class PayuResponse
{
    /**
     * @var string
     * @JMS\Type("string")
     *
     * code
     */
    protected $code;

    /**
     * Sets Code
     *
     * @param string $code Code
     *
     * @return PayuResponse Self object
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get Code
     *
     * @return string Code
     */
    public function getCode()
    {
        return $this->code;
    }
}