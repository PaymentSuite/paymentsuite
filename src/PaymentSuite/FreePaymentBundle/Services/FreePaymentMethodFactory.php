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

namespace PaymentSuite\FreePaymentBundle\Services;

use PaymentSuite\FreePaymentBundle\FreePaymentMethod;
use PaymentSuite\FreePaymentBundle\Services\Interfaces\FreePaymentSettingsProviderInterface;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class FreePaymentMethodFactory.
 */
class FreePaymentMethodFactory
{
    /**
     * @var FreePaymentSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * FreePaymentMethodFactory constructor.
     *
     * @param FreePaymentSettingsProviderInterface $settingsProvider
     */
    public function __construct(FreePaymentSettingsProviderInterface $settingsProvider)
    {
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * Create new PaymentMethodInterface instance.
     *
     * @return PaymentMethodInterface New instance
     */
    public function create()
    {
        return new FreePaymentMethod($this->settingsProvider->getPaymentName());
    }
}
