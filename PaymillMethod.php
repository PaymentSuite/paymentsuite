<?php

/**
 * BeFactory Payments Suite
 *
 * Befactory 2013
 */

namespace Befactory\PaymillBundle;

use Befactory\CorePaymentBundle\PaymentMethodInterface;


class PaymillMethod implements PaymentMethodInterface
{

    /**
     * @inherit
     */
    public function getPaymentName()
    {
        return 'Paymill';
    }


    private $amount;
    private $creditCartNumber;
    private $creditCartOwner;
    private $creditCartExpirationYear;
    private $creditCartExpirationMonth;
    private $creditCartSecurity;
    private $creditCartToken;
    private $transactionId;


    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }


    public function getAmount()
    {
        return $this->amount;
    }


    public function setCreditCartNumber($creditCartNumber)
    {
        $this->creditCartNumber = $this->creditCartNumber;

        return $this;
    }


    public function getCreditCartNumber()
    {
        return $this->creditCartNumber;
    }


    public function setCreditCartOwner($creditCartOwner)
    {
        $this->creditCartOwner = $creditCartOwner;

        return $this;
    }


    public function getCreditCartOwner()
    {
        return $this->creditCartOwner;
    }


    public function setCreditCartExpirationYear($creditCartExpirationYear)
    {
        $this->creditCartExpirationYear = $creditCartExpirationYear;

        return $this;
    }


    public function getCreditCartExpirationYear()
    {
        return $this->creditCartExpirationYear;
    }


    public function setCreditCartExpirationMonth($creditCartExpirationMonth)
    {
        $this->creditCartExpirationMonth = $creditCartExpirationMonth;

        return $this;
    }


    public function getCreditCartExpirationMonth()
    {
        return $this->creditCartExpirationMonth;
    }


    public function setCreditCartSecurity($creditCartSecurity)
    {
        $this->creditCartSecurity = $creditCartSecurity;

        return $this;
    }


    public function getCreditCartSecurity()
    {
        return $this->creditCartSecurity;
    }


    public function setCreditCartToken($creditCartToken)
    {
        $this->creditCartToken = $creditCartToken;

        return $this;
    }


    public function getCreditCartToken()
    {
        return $this->creditCartToken;
    }


    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }


    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }

    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

}