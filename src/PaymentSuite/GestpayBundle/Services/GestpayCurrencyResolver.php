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

namespace PaymentSuite\GestpayBundle\Services;

use EndelWar\GestPayWS\Data\Currency;
use PaymentSuite\GestpayBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class GestpayCurrencyResolver.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayCurrencyResolver
{
    /**
     * @var PaymentBridgeInterface
     */
    private $paymentBridge;

    /**
     * GestpayCurrencyResolver constructor.
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }

    /**
     * @return int
     *
     * @throws CurrencyNotSupportedException
     */
    public function getCurrencyCode()
    {
        $currency = $this->paymentBridge->getCurrency();

        $constantName = sprintf('%s::%s', Currency::class, strtoupper($currency));

        if (!defined($constantName)) {
            throw new CurrencyNotSupportedException();
        }

        return constant($constantName);
    }
}
