<?php

namespace Scastells\PagosonlineBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


/**
 * PaymillMethod class
 */
class PagosonlineMethod implements PaymentMethodInterface
{

    /**
     * Get Pagosonline method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Pagosonline';
    }


    /**
     * @var float
     *
     * Pagosonline amount
     */
    private $amount;


    /*
     * @var string
     *
     * Credit card owner
     */
    private $cardName;


    /*
     * @var string
     *
     * Credit card type
     */
    private $cardType;


    /**
     * @var string
     *
     * Credit card number
     */
    private $cardNum;


    /**
     * @var integer
     *
     * Card expiration month
     */
    private $cardExpMonth;


    /*
     * @var integer
     *
     * Card expiration year
     */
    private $cardExpYear;


    /*
     * @var string
     *
     * Card security value
     */
    private $cardSecurity;


    /*
     * @var integer
     *
     * Number of payment quota
     */
    private $cardQuota;


    /**
     * @var string
     *
     * user agent
     */
    private $userAgent;


    /**
     * @var string
     *
     * client ip
     */
    private $clientIp;


    /**
     * @var string
     *
     * cookie
     */
    private $cookie;


    /**
     * @var string
     *
     * pagosonline transactionid
     */
    private $pagosonlineTransactionId;


    /**
     * @var string
     *
     * pagosonline reference
     */

    private $pagosonlineReference;

    /**
     * @param string $pagosonlineTransactionId
     *
     * @return $this
     */
    public function setPagosonlineTransactionId($pagosonlineTransactionId)
    {
        $this->pagosonlineTransactionId = $pagosonlineTransactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPagosonlineTransactionId()
    {
        return $this->pagosonlineTransactionId;
    }

    /**
     * @param string $pagosonlineReference
     *
     * @return $this
     */
    public function setPagosonlineReference($pagosonlineReference)
    {
        $this->pagosonlineReference = $pagosonlineReference;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosonlineReference()
    {
        return $this->pagosonlineReference;
    }

    /**
     * @param float $amount
     *
     * @return  PagosonlineMethod self Object
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $cardExpMonth
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardExpMonth($cardExpMonth)
    {
        $this->cardExpMonth = $cardExpMonth;

        return $this;
    }

    /**
     * @return int
     */
    public function getCardExpMonth()
    {
        return $this->cardExpMonth;
    }

    /**
     * @param mixed $cardExpYear
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardExpYear($cardExpYear)
    {
        $this->cardExpYear = $cardExpYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardExpYear()
    {
        return $this->cardExpYear;
    }

    /**
     * @param mixed $cardName
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardName($cardName)
    {
        $this->cardName = $cardName;

        return $this;
    }

    /**
     * @return string $cardName
     */
    public function getCardName()
    {
        return $this->cardName;
    }

    /**
     * @param string $cardNum
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardNum($cardNum)
    {
        $this->cardNum = $cardNum;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardNum()
    {
        return $this->cardNum;
    }

    /**
     * @param mixed $cardQuota
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardQuota($cardQuota)
    {
        $this->cardQuota = $cardQuota;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardQuota()
    {
        return $this->cardQuota;
    }

    /**
     * @param mixed $cardSecurity
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardSecurity($cardSecurity)
    {
        $this->cardSecurity = $cardSecurity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardSecurity()
    {
        return $this->cardSecurity;
    }

    /**
     * @param mixed $cardType
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param string $clientIp
     *
     * @return  PagosonlineMethod self Object
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @param string $userAgent
     *
     * @return  PagosonlineMethod self Object
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }


    /**
     * @param string cookie
     *
     * @return  PagosonlineMethod self Object
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

}