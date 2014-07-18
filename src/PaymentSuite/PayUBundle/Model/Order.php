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
 * Order Model
 */
class Order
{
    /**
     * @var string
     *
     * accountId
     */
    protected $accountId;

    /**
     * @var string
     *
     * referenceCode
     */
    protected $referenceCode;

    /**
     * @var string
     *
     * description
     */
    protected $description;

    /**
     * @var string
     *
     * language
     */
    protected $language;

    /**
     * @var string
     *
     * notifyUrl
     */
    protected $notifyUrl;

    /**
     * @var string
     *
     * partnerId
     */
    protected $partnerId;

    /**
     * @var string
     *
     * signature
     */
    protected $signature;

    /**
     * @var Address
     *
     * shippingAddress
     */
    protected $shippingAddress;

    /**
     * @var User
     *
     * buyer
     */
    protected $buyer;

    /**
     * @var array
     *
     * additionalValues
     */
    protected $additionalValues;

    /**
     * Sets AccountId
     *
     * @param string $accountId AccountId
     *
     * @return Order Self object
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get AccountId
     *
     * @return string AccountId
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Sets AdditionalValues
     *
     * @param AdditionalValue $additionalValue Additional Value
     * @param string          $type            Additional Value type
     *
     * @return Order Self object
     */
    public function setAdditionalValues(AdditionalValue $additionalValue, $type)
    {
        $value = [
            $type => $additionalValue
        ];
        $this->additionalValues = $value;

        return $this;
    }

    /**
     * Get AdditionalValues
     *
     * @return array AdditionalValues
     */
    public function getAdditionalValues()
    {
        return $this->additionalValues;
    }

    /**
     * Sets Buyer
     *
     * @param \PaymentSuite\PayuBundle\Model\User $buyer Buyer
     *
     * @return Order Self object
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get Buyer
     *
     * @return \PaymentSuite\PayuBundle\Model\User Buyer
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Sets Description
     *
     * @param string $description Description
     *
     * @return Order Self object
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get Description
     *
     * @return string Description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets Language
     *
     * @param string $language Language
     *
     * @return Order Self object
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
     * Sets NotifyUrl
     *
     * @param string $notifyUrl NotifyUrl
     *
     * @return Order Self object
     */
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;

        return $this;
    }

    /**
     * Get NotifyUrl
     *
     * @return string NotifyUrl
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * Sets PartnerId
     *
     * @param string $partnerId PartnerId
     *
     * @return Order Self object
     */
    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    /**
     * Get PartnerId
     *
     * @return string PartnerId
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * Sets ReferenceCode
     *
     * @param string $referenceCode ReferenceCode
     *
     * @return Order Self object
     */
    public function setReferenceCode($referenceCode)
    {
        $this->referenceCode = $referenceCode;

        return $this;
    }

    /**
     * Get ReferenceCode
     *
     * @return string ReferenceCode
     */
    public function getReferenceCode()
    {
        return $this->referenceCode;
    }

    /**
     * Sets ShippingAddress
     *
     * @param \PaymentSuite\PayuBundle\Model\Address $shippingAddress ShippingAddress
     *
     * @return Order Self object
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get ShippingAddress
     *
     * @return \PaymentSuite\PayuBundle\Model\Address ShippingAddress
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Sets Signature
     *
     * @param string $signature Signature
     *
     * @return Order Self object
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get Signature
     *
     * @return string Signature
     */
    public function getSignature()
    {
        return $this->signature;
    }
}
