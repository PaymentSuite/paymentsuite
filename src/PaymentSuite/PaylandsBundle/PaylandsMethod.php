<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaylandsBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class PaylandsMethod.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
final class PaylandsMethod implements PaymentMethodInterface
{
    const STATUS_OK = 'OK';

    const STATUS_KO = 'KO';

    /**
     * @var string
     */
    private $customerExternalId;

    /**
     * @var string
     */
    private $customerToken;

    /**
     * @var string
     */
    private $cardBin;

    /**
     * @var string
     */
    private $cardBrand;

    /**
     * @var string
     */
    private $cardCountry;

    /**
     * @var string
     */
    private $cardExpireMonth;

    /**
     * @var string
     */
    private $cardExpireYear;

    /**
     * @var string
     */
    private $cardLast4;

    /**
     * @var string
     */
    private $cardType;

    /**
     * @var string
     */
    private $cardUuid;

    /**
     * @var string
     */
    private $cardAdditional;

    /**
     * @var bool
     */
    private $onlyTokenizeCard;

    /**
     * @var array
     */
    private $paymentResult;

    /**
     * @var string
     */
    private $paymentStatus;

    /**
     * @var string
     */
    private $paymentName;

    /**
     * PaylandsMethod constructor.
     *
     * @param string $paymentName
     */
    public function __construct(string $paymentName)
    {
        $this->paymentName = $paymentName;
    }

    /**
     * @return string
     */
    public function getCustomerExternalId()
    {
        return $this->customerExternalId;
    }

    /**
     * @param string $customerExternalId
     *
     * @return PaylandsMethod
     */
    public function setCustomerExternalId($customerExternalId)
    {
        $this->customerExternalId = $customerExternalId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerToken()
    {
        return $this->customerToken;
    }

    /**
     * @param string $customerToken
     *
     * @return PaylandsMethod
     */
    public function setCustomerToken($customerToken)
    {
        $this->customerToken = $customerToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardBin()
    {
        return $this->cardBin;
    }

    /**
     * @param string $cardBin
     *
     * @return PaylandsMethod
     */
    public function setCardBin($cardBin)
    {
        $this->cardBin = $cardBin;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardBrand()
    {
        return $this->cardBrand;
    }

    /**
     * @param string $cardBrand
     *
     * @return PaylandsMethod
     */
    public function setCardBrand($cardBrand)
    {
        $this->cardBrand = $cardBrand;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardCountry()
    {
        return $this->cardCountry;
    }

    /**
     * @param string $cardCountry
     *
     * @return PaylandsMethod
     */
    public function setCardCountry($cardCountry)
    {
        $this->cardCountry = $cardCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpireMonth()
    {
        return $this->cardExpireMonth;
    }

    /**
     * @param string $cardExpireMonth
     *
     * @return PaylandsMethod
     */
    public function setCardExpireMonth($cardExpireMonth)
    {
        $this->cardExpireMonth = $cardExpireMonth;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpireYear()
    {
        return $this->cardExpireYear;
    }

    /**
     * @param string $cardExpireYear
     *
     * @return PaylandsMethod
     */
    public function setCardExpireYear($cardExpireYear)
    {
        $this->cardExpireYear = $cardExpireYear;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardLast4()
    {
        return $this->cardLast4;
    }

    /**
     * @param string $cardLast4
     *
     * @return PaylandsMethod
     */
    public function setCardLast4($cardLast4)
    {
        $this->cardLast4 = $cardLast4;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param string $cardType
     *
     * @return PaylandsMethod
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardUuid()
    {
        return $this->cardUuid;
    }

    /**
     * @param string $cardUuid
     *
     * @return PaylandsMethod
     */
    public function setCardUuid($cardUuid)
    {
        $this->cardUuid = $cardUuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardAdditional()
    {
        return $this->cardAdditional;
    }

    /**
     * @param string $cardAdditional
     *
     * @return PaylandsMethod
     */
    public function setCardAdditional($cardAdditional)
    {
        $this->cardAdditional = $cardAdditional;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyTokenizeCard()
    {
        return (bool) $this->onlyTokenizeCard;
    }

    /**
     * @param bool $onlyTokenizeCard
     *
     * @return PaylandsMethod
     */
    public function setOnlyTokenizeCard($onlyTokenizeCard)
    {
        $this->onlyTokenizeCard = $onlyTokenizeCard;

        return $this;
    }

    /**
     * @return array
     */
    public function getPaymentResult()
    {
        return $this->paymentResult;
    }

    /**
     * @param array $paymentResult
     *
     * @return PaylandsMethod
     */
    public function setPaymentResult($paymentResult)
    {
        $this->paymentResult = $paymentResult;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     *
     * @return PaylandsMethod
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Gets Paylands method name.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }
}
