<?php

/**
 * BanwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package BanwireBundle
 *
 */

namespace Scastells\BanwireBundle;

use Mmoreram\PaymentCoreBundle\PaymentMethodInterface;


/**
 * BanwireMethod class
 */
class BanwireMethod implements PaymentMethodInterface
{

    /**
     * Get BanwireMethod method name
     * 
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'Banwire';
    }


    /**
     * @var float
     *
     * Banwire amount
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


    /**
     * @var string
     *
     * banwire transactionid
     */
    private $banwireTransactionId;


    /**
     * @var string
     *
     * banwire reference
     */

    private $banwireReference;

    /**
     * @param string $banwireTransactionId
     *
     * @return $this
     */
    public function setBanwireTransactionId($banwireTransactionId)
    {
        $this->banwireTransactionId = $banwireTransactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getBanwireTransactionId()
    {
        return $this->banwireTransactionId;
    }

    /**
     * @param string $banwireReference
     *
     * @return $this
     */
    public function setBanwireReference($banwireReference)
    {
        $this->banwireReference = $banwireReference;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBanwireReference()
    {
        return $this->banwireReference;
    }

    /**
     * @param float $amount
     *
     * @return  BanwireMethod self Object
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
     * @return  BanwireMethod self Object
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
     * @return  BanwireMethod self Object
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
     * @return  BanwireMethod self Object
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
     * @return  BanwireMethod self Object
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
     * @param mixed $cardSecurity
     *
     * @return  BanwireMethod self Object
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
     * @return  BanwireMethod self Object
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

}