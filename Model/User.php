<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Model;

/**
 * User Model
 */
class User
{
    /**
     * @var string
     *
     * merchantBuyerId
     */
    protected $merchantBuyerId;

    /**
     * @var string
     *
     * fullName
     */
    protected $fullName;

    /**
     * @var string
     *
     * emailAddress
     */
    protected $emailAddress;

    /**
     * @var string
     *
     * contactPhone
     */
    protected $contactPhone;

    /**
     * @var string
     *
     * dniNumber
     */
    protected $dniNumber;

    /**
     * @var string
     *
     * cnpj
     */
    protected $cnpj;

    /**
     * @var Address
     *
     * shippingAddress
     */
    protected $shippingAddress;

    /**
     * Sets Cnpj
     *
     * @param string $cnpj Cnpj
     *
     * @return User Self object
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get Cnpj
     *
     * @return string Cnpj
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Sets ContactPhone
     *
     * @param string $contactPhone ContactPhone
     *
     * @return User Self object
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get ContactPhone
     *
     * @return string ContactPhone
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Sets DniNumber
     *
     * @param string $dniNumber DniNumber
     *
     * @return User Self object
     */
    public function setDniNumber($dniNumber)
    {
        $this->dniNumber = $dniNumber;

        return $this;
    }

    /**
     * Get DniNumber
     *
     * @return string DniNumber
     */
    public function getDniNumber()
    {
        return $this->dniNumber;
    }

    /**
     * Sets EmailAddress
     *
     * @param string $emailAddress EmailAddress
     *
     * @return User Self object
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get EmailAddress
     *
     * @return string EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Sets FullName
     *
     * @param string $fullName FullName
     *
     * @return User Self object
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get FullName
     *
     * @return string FullName
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Sets MerchantBuyerId
     *
     * @param string $merchantBuyerId MerchantBuyerId
     *
     * @return User Self object
     */
    public function setMerchantBuyerId($merchantBuyerId)
    {
        $this->merchantBuyerId = $merchantBuyerId;

        return $this;
    }

    /**
     * Get MerchantBuyerId
     *
     * @return string MerchantBuyerId
     */
    public function getMerchantBuyerId()
    {
        return $this->merchantBuyerId;
    }

    /**
     * Sets ShippingAddress
     *
     * @param Address $shippingAddress ShippingAddress
     *
     * @return User Self object
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get ShippingAddress
     *
     * @return Address ShippingAddress
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
}