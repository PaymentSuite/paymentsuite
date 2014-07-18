<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <canis.viridi@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2013
 */

namespace PaymentSuite\RedsysBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * RedsysMethod class
 */
class RedsysMethod implements PaymentMethodInterface
{
    /**
     * @var string
     *
     * Transaction response
     */
    private $dsResponse;

    /**
     * @var string
     *
     * Transaction date
     */
    private $dsDate;

    /**
     * @var string
     *
     * Transaction hour
     */
    private $dsHour;

    /**
     *  @var string
     *
     * Transaction secure payment
     */
    private $dsSecurePayment;

    /**
     *  @var string
     *
     * Transaction card country
     */
    private $dsCardCountry;

    /**
     *  @var string
     *
     * Transaction authorisation code
     */
    private $dsAuthorisationCode;

    /**
     *  @var string
     *
     * Transaction consumer language
     */
    private $dsConsumerLanguage;

    /**
     *  @var string
     *
     * Transaction card type
     */
    private $dsCardType;

    /**
     * Get Redsys method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Redsys';
    }

    /**
     * set Response code
     *
     * @param string $dsResponse Response code
     *
     * @return RedsysMethod self Object
     */
    public function setDsResponse($dsResponse)
    {
        $this->dsResponse = $dsResponse;

        return $this;
    }

    /**
     * Get Response code
     *
     * @return string Response code
     */
    public function getDsResponse()
    {
        return $this->dsResponse;

    }
    /**
     * set Authorisation code
     *
     * @param string $dsAuthorisationCode
     *
     * @return RedsysMethod self Object
     */
    public function setDsAuthorisationCode($dsAuthorisationCode)
    {
        $this->dsAuthorisationCode = $dsAuthorisationCode;

        return $this;
    }

    /**
     * Get Authorisation code
     *
     * @return string
     */
    public function getDsAuthorisationCode()
    {
        return $this->dsAuthorisationCode;
    }

    /**
     *  Set Card country
     *
     * @param string $dsCardCountry
     *
     * @return RedsysMethod self Object
     */
    public function setDsCardCountry($dsCardCountry)
    {
        $this->dsCardCountry = $dsCardCountry;

        return $this;
    }

    /**
     * Get Card country
     *
     * @return string
     */
    public function getDsCardCountry()
    {
        return $this->dsCardCountry;
    }

    /**
     * Set Card type
     *
     * @param string $dsCardType
     *
     * @return RedsysMethod self Object
     */
    public function setDsCardType($dsCardType)
    {
        $this->dsCardType = $dsCardType;

        return $this;
    }

    /**
     * Get Card type
     *
     * @return string
     */
    public function getDsCardType()
    {
        return $this->dsCardType;
    }

    /**
     * Set consumer language
     *
     * @param string $dsConsumerLanguage
     *
     * @return RedsysMethod self Object
     */
    public function setDsConsumerLanguage($dsConsumerLanguage)
    {
        $this->dsConsumerLanguage = $dsConsumerLanguage;

        return $this;
    }

    /**
     * Get Consumer language
     *
     * @return string
     */
    public function getDsConsumerLanguage()
    {
        return $this->dsConsumerLanguage;
    }

    /**
     * Set date
     *
     * @param string $dsDate
     *
     * @return RedsysMethod self Object
     */
    public function setDsDate($dsDate)
    {
        $this->dsDate = $dsDate;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDsDate()
    {
        return $this->dsDate;
    }

    /**
     * Set hour
     *
     * @param string $dsHour
     *
     * @return RedsysMethod self Object
     */
    public function setDsHour($dsHour)
    {
        $this->dsHour = $dsHour;

        return $this;
    }

    /**
     * Get Hour
     *
     * @return string
     */
    public function getDsHour()
    {
        return $this->dsHour;
    }

    /**
     * Set Secure payment
     *
     * @param string $dsSecurePayment
     *
     * @return RedsysMethod self Object
     */
    public function setDsSecurePayment($dsSecurePayment)
    {
        $this->dsSecurePayment = $dsSecurePayment;

        return $this;
    }

    /**
     * Get Secure payment
     *
     * @return string
     */
    public function getDsSecurePayment()
    {
        return $this->dsSecurePayment;
    }

}
