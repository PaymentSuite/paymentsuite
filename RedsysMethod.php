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

    private $dsDate;

    private $dsHour;

    private $dsSecurePayment;

    private $dsCardCountry;

    private $dsAuthorisationCode;

    private $dsConsumerLanguage;

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
     * @return string
     */
    public function getDsAuthorisationCode()
    {
        return $this->dsAuthorisationCode;
    }

    /**
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
     * @return string
     */
    public function getDsCardCountry()
    {
        return $this->dsCardCountry;
    }

    /**
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
     * @return string
     */
    public function getDsCardType()
    {
        return $this->dsCardType;
    }

    /**
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
     * @return string
     */
    public function getDsConsumerLanguage()
    {
        return $this->dsConsumerLanguage;
    }

    /**
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
     * @return string
     */
    public function getDsDate()
    {
        return $this->dsDate;
    }

    /**
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
     * @return string
     */
    public function getDsHour()
    {
        return $this->dsHour;
    }

    /**
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
     * @return string
     */
    public function getDsSecurePayment()
    {
        return $this->dsSecurePayment;
    }



}