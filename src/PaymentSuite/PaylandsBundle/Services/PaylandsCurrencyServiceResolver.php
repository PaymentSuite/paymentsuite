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

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Services\Interfaces\PaylandsSettingsProviderInterface;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class PaylandsCurrencyServiceResolver.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsCurrencyServiceResolver
{
    /**
     * @var PaymentBridgeInterface
     */
    protected $paymentBridge;

    /**
     * @var PaylandsSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * PaylandsCurrencyServiceResolver constructor.
     *
     * @param PaymentBridgeInterface            $paymentBridge
     * @param PaylandsSettingsProviderInterface $settingsProvider
     */
    public function __construct(
        PaymentBridgeInterface $paymentBridge,
        PaylandsSettingsProviderInterface $settingsProvider
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * Gets the available service ID for currency indicated by PaymentBridge.
     *
     * @return string Service ID for the currency
     */
    public function getService()
    {
        $currency = $this->paymentBridge->getCurrency();

        $paymentServices = $this->settingsProvider->getPaymentServices();
        if (key_exists($currency, $paymentServices)) {
            return $paymentServices[$currency];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getValidationService()
    {
        if (!is_null($this->settingsProvider->getValidationService())) {
            return $this->settingsProvider->getValidationService();
        }

        return $this->getService();
    }
}
