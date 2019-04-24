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

namespace PaymentSuite\BankwireBundle\Services;

use PaymentSuite\BankwireBundle\BankwireMethod;
use PaymentSuite\BankwireBundle\Services\Interfaces\BankwireSettingsProviderInterface;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class BankwireMethodFactory.
 */
class BankwireMethodFactory
{
    /**
     * @var BankwireSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * BankwireMethodFactory constructor.
     *
     * @param BankwireSettingsProviderInterface $settingsProvider
     */
    public function __construct(BankwireSettingsProviderInterface $settingsProvider)
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
        return new BankwireMethod($this->settingsProvider->getPaymentName());
    }
}
