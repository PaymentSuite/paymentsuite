<?php

namespace PaymentSuite\RedsysBundle;

/**
 * Class RedsysSignature.
 */
class RedsysSignature
{
    /**
     * @var string
     */
    private $data;

    /**
     * RedsysSignature constructor.
     *
     * @param string $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param RedsysSignature $signature
     *
     * @return bool
     */
    public function match(RedsysSignature $signature)
    {
        return $this->data === $signature->data;
    }

    public function __toString()
    {
        return $this->data;
    }
}
