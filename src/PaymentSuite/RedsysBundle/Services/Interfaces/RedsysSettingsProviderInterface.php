<?php

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

interface RedsysSettingsProviderInterface extends PaymentMethodInterface
{
    /**
     * Gets Redsys merchant code.
     *
     * Example: "099888777"
     *
     * @return string
     */
    public function getMerchanCode(): string;

    /**
     * Gets Redsys terminal number.
     *
     * Example: "002"
     *
     * @return string
     */
    public function getTerminal(): string;

    /**
     * Gets Redsys sha256 secret key.
     *
     * @return string
     */
    public function getSecretKey(): string;
}
