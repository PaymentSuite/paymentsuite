<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Model\Abstracts;

use PaymentSuite\PayuBundle\Model\Merchant;

/**
 * Abstract Model class for request models
 */
abstract class PayuRequest
{
    /**
     * @var string
     *
     * language
     */
    protected $language;

    /**
     * @var string
     *
     * command
     */
    protected $command;

    /**
     * @var boolean
     *
     * test
     */
    protected $test;

    /**
     * @var Merchant
     *
     * merchant
     */
    protected $merchant;

    /**
     * Sets Command
     *
     * @param string $command Command
     *
     * @return PayuRequest Self object
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get Command
     *
     * @return string Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Sets Language
     *
     * @param string $language Language
     *
     * @return PayuRequest Self object
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get Language
     *
     * @return string Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets Test
     *
     * @param boolean $test Test
     *
     * @return PayuRequest Self object
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get Test
     *
     * @return boolean Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Sets Merchant
     *
     * @param Merchant $merchant Merchant
     *
     * @return PayuRequest Self object
     */
    public function setMerchant($merchant)
    {
        $this->merchant = $merchant;

        return $this;
    }

    /**
     * Get Merchant
     *
     * @return Merchant Merchant
     */
    public function getMerchant()
    {
        return $this->merchant;
    }
}
