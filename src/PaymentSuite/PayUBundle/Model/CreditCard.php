<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Model;

/**
 * CreditCard Model
 */
class CreditCard
{
    /**
     * @var string
     *
     * number
     */
    protected $number;

    /**
     * @var string
     *
     * securityCode
     */
    protected $securityCode;

    /**
     * @var string
     *
     * expirationDate
     */
    protected $expirationDate;

    /**
     * @var string
     *
     * name
     */
    protected $name;

    /**
     * @var string
     *
     * issuerBank
     */
    protected $issuerBank;

    /**
     * @var boolean
     *
     * processWithoutCvv2
     */
    protected $processWithoutCvv2;

    /**
     * Sets ExpirationDate
     *
     * @param string $expirationDate ExpirationDate
     *
     * @return CreditCard Self object
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get ExpirationDate
     *
     * @return string ExpirationDate
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Sets IssuerBank
     *
     * @param string $issuerBank IssuerBank
     *
     * @return CreditCard Self object
     */
    public function setIssuerBank($issuerBank)
    {
        $this->issuerBank = $issuerBank;

        return $this;
    }

    /**
     * Get IssuerBank
     *
     * @return string IssuerBank
     */
    public function getIssuerBank()
    {
        return $this->issuerBank;
    }

    /**
     * Sets Name
     *
     * @param string $name Name
     *
     * @return CreditCard Self object
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Name
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets Number
     *
     * @param string $number Number
     *
     * @return CreditCard Self object
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get Number
     *
     * @return string Number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets ProcessWithoutCvv2
     *
     * @param boolean $processWithoutCvv2 ProcessWithoutCvv2
     *
     * @return CreditCard Self object
     */
    public function setProcessWithoutCvv2($processWithoutCvv2)
    {
        $this->processWithoutCvv2 = $processWithoutCvv2;

        return $this;
    }

    /**
     * Get ProcessWithoutCvv2
     *
     * @return boolean ProcessWithoutCvv2
     */
    public function getProcessWithoutCvv2()
    {
        return $this->processWithoutCvv2;
    }

    /**
     * Sets SecurityCode
     *
     * @param string $securityCode SecurityCode
     *
     * @return CreditCard Self object
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    /**
     * Get SecurityCode
     *
     * @return string SecurityCode
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }
}
