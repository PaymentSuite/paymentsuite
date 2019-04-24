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

namespace PaymentSuite\StripeBundle\Services;

use Exception;
use PaymentSuite\StripeBundle\Services\Interfaces\StripeSettingsProviderInterface;
use PaymentSuite\StripeBundle\ValueObject\StripeTransaction;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

/**
 * class StripeTransactionFactory.
 */
class StripeTransactionFactory
{
    /**
     * @var StripeEventDispatcher
     */
    private $dispatcher;
    /**
     * @var StripeSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * Construct method for stripe transaction wrapper.
     *
     * @param StripeSettingsProviderInterface $settingsProvider
     * @param StripeEventDispatcher           $dispatcher
     */
    public function __construct(StripeSettingsProviderInterface $settingsProvider, StripeEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * Create new Transaction with a set of params.
     *
     * @param array $transaction Set of params [source, amount, currency]
     *
     * @return \ArrayAccess|array Result of transaction
     */
    public function create(StripeTransaction $transaction)
    {
        try {
            Stripe::setApiKey($this->settingsProvider->getPrivateKey());

            $this->dispatcher->notifyCustomerPreCreate($transaction);

            $customer = Customer::create($transaction->getCustomerData());

            $transaction->setCustomerId($customer->id);

            $chargeData = Charge::create($transaction->getChargeData());
        } catch (Exception $e) {
            // The way to get to 'notifyPaymentOrderFail'
            return [
                'paid' => 0,
            ];
        }

        return $chargeData;
    }
}
