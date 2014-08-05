<?php

/**
 * DineromailApiBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 *
 */

namespace PaymentSuite\DineroMailApiBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * DineromailApiMethod class
 */
class DineromailApiMethod implements PaymentMethodInterface
{
    /**
     * Get DineromailApiMethod method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'DineromailApi';
    }

    /**
     * @var float
     *
     * DineromailApi amount
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
     * dineromail transactionid
     */
    private $dineromailApiTransactionId;

    /**
     * @var string
     *
     * dineromail reference
     */

    private $dineromailApiReference;

    /**
     * @var integer
     *
     * dineromail quota
     */

    private $cardQuota;

    /**
     * @param string $dineromailApiTransactionId
     *
     * @return $this
     */
    public function setDineromailApiTransactionId($dineromailApiTransactionId)
    {
        $this->dineromailApiTransactionId = $dineromailApiTransactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDineromailApiTransactionId()
    {
        return $this->dineromailApiTransactionId;
    }

    /**
     * @param string $dineromailApiReference
     *
     * @return $this
     */
    public function setDineromailApiReference($dineromailApiReference)
    {
        $this->dineromailApiReference = $dineromailApiReference;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDineromailApiReference()
    {
        return $this->dineromailApiReference;
    }

    /**
     * @param float $amount
     *
     * @return DineromailApiMethod self Object
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
     * @return DineromailApiMethod self Object
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
     * @return DineromailApiMethod self Object
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
     * @return DineromailApiMethod self Object
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
     * @return DineromailApiMethod self Object
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
     * @return DineromailApiMethod self Object
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
     * @return DineromailApiMethod self Object
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
     * @param mixed $cardQuota
     *
     * @return DineromailApiMethod self Object
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

}
