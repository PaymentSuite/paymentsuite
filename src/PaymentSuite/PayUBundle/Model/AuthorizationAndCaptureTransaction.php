<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Model;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuTransaction;

/**
 * Authorization and Capture Transaction Model
 */
class AuthorizationAndCaptureTransaction extends PayuTransaction
{
    /**
     * @var Order
     *
     * order
     */
    protected $order;

    /**
     * @var CreditCard
     *
     * creditCard
     */
    protected $creditCard;

    /**
     * @var User
     *
     * payer
     */
    protected $payer;

    /**
     * @var string
     *
     * paymentMethod
     */
    protected $paymentMethod;

    /**
     * @var string
     *
     * source
     */
    protected $source;

    /**
     * @var string
     *
     * expirationDate
     */
    protected $expirationDate;

    /**
     * @var string
     *
     * deviceSessionId
     */
    protected $deviceSessionId;

    /**
     * @var string
     *
     * ipAddress
     */
    protected $ipAddress;

    /**
     * @var string
     *
     * cookie
     */
    protected $cookie;

    /**
     * @var string
     *
     * userAgent
     */
    protected $userAgent;

    /**
     * @var array
     *
     * extraParameters
     */
    protected $extraParameters;

    /**
     * @var boolean
     *
     * termsAndConditionsAcepted
     */
    protected $termsAndConditionsAcepted;

    /**
     * Sets Cookie
     *
     * @param string $cookie Cookie
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Get Cookie
     *
     * @return string Cookie
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Sets CreditCard
     *
     * @param \PaymentSuite\PayuBundle\Model\CreditCard $creditCard CreditCard
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setCreditCard($creditCard)
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    /**
     * Get CreditCard
     *
     * @return \PaymentSuite\PayuBundle\Model\CreditCard CreditCard
     */
    public function getCreditCard()
    {
        return $this->creditCard;
    }

    /**
     * Sets DeviceSessionId
     *
     * @param string $deviceSessionId DeviceSessionId
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setDeviceSessionId($deviceSessionId)
    {
        $this->deviceSessionId = $deviceSessionId;

        return $this;
    }

    /**
     * Get DeviceSessionId
     *
     * @return string DeviceSessionId
     */
    public function getDeviceSessionId()
    {
        return $this->deviceSessionId;
    }

    /**
     * Sets ExpirationDate
     *
     * @param string $expirationDate ExpirationDate
     *
     * @return AuthorizationAndCaptureTransaction Self object
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
     * Sets ExtraParameters
     *
     * @param array $extraParameters ExtraParameters
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setExtraParameters($extraParameters)
    {
        $this->extraParameters = $extraParameters;

        return $this;
    }

    /**
     * Get ExtraParameters
     *
     * @return array ExtraParameters
     */
    public function getExtraParameters()
    {
        return $this->extraParameters;
    }

    /**
     * Sets IpAddress
     *
     * @param string $ipAddress IpAddress
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get IpAddress
     *
     * @return string IpAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Sets Order
     *
     * @param \PaymentSuite\PayuBundle\Model\Order $order Order
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get Order
     *
     * @return \PaymentSuite\PayuBundle\Model\Order Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets Payer
     *
     * @param \PaymentSuite\PayuBundle\Model\User $payer Payer
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get Payer
     *
     * @return \PaymentSuite\PayuBundle\Model\User Payer
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Sets PaymentMethod
     *
     * @param string $paymentMethod PaymentMethod
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get PaymentMethod
     *
     * @return string PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Sets Source
     *
     * @param string $source Source
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get Source
     *
     * @return string Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Sets TermsAndConditionsAcepted
     *
     * @param boolean $termsAndConditionsAcepted TermsAndConditionsAcepted
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setTermsAndConditionsAcepted($termsAndConditionsAcepted)
    {
        $this->termsAndConditionsAcepted = $termsAndConditionsAcepted;

        return $this;
    }

    /**
     * Get TermsAndConditionsAcepted
     *
     * @return boolean TermsAndConditionsAcepted
     */
    public function getTermsAndConditionsAcepted()
    {
        return $this->termsAndConditionsAcepted;
    }

    /**
     * Sets UserAgent
     *
     * @param string $userAgent UserAgent
     *
     * @return AuthorizationAndCaptureTransaction Self object
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get UserAgent
     *
     * @return string UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}