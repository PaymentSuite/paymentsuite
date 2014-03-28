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
 * Address Model
 */
class Address
{
    /**
     * @var string
     *
     * street1
     */
    protected $street1;

    /**
     * @var string
     *
     * street2
     */
    protected $street2;

    /**
     * @var string
     *
     * city
     */
    protected $city;

    /**
     * @var string
     *
     * state
     */
    protected $state;

    /**
     * @var string
     *
     * country
     */
    protected $country;

    /**
     * @var string
     *
     * postalCode
     */
    protected $postalCode;

    /**
     * @var string
     *
     * phone
     */
    protected $phone;

    /**
     * Sets City
     *
     * @param string $city City
     *
     * @return Address Self object
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get City
     *
     * @return string City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets Country
     *
     * @param string $country Country
     *
     * @return Address Self object
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get Country
     *
     * @return string Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets Phone
     *
     * @param string $phone Phone
     *
     * @return Address Self object
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get Phone
     *
     * @return string Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets PostalCode
     *
     * @param string $postalCode PostalCode
     *
     * @return Address Self object
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get PostalCode
     *
     * @return string PostalCode
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Sets State
     *
     * @param string $state State
     *
     * @return Address Self object
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get State
     *
     * @return string State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets Street1
     *
     * @param string $street1 Street1
     *
     * @return Address Self object
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;

        return $this;
    }

    /**
     * Get Street1
     *
     * @return string Street1
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * Sets Street2
     *
     * @param string $street2 Street2
     *
     * @return Address Self object
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;

        return $this;
    }

    /**
     * Get Street2
     *
     * @return string Street2
     */
    public function getStreet2()
    {
        return $this->street2;
    }
}