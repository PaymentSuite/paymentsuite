<?php
/*
 * This file is part of the Mascoteros package.
 *
 * Copyright (c) 2015-2016 Mascoteros.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */
namespace PaymentSuite\AdyenBundle\Services;

use Adyen\Service\Payment;
use Adyen\Service\Checkout;
use Adyen\Service\Recurring;

class AdyenClientService
{
    protected $client;

    /**
     * AdyenClientService constructor.
     *
     * @param string $applicationName
     * @param string $username
     * @param string $password
     * @param string $environment
     * @param null $xApiKey
     *
     * @throws \Adyen\AdyenException
     */
    public function __construct(
        $applicationName,
        $username,
        $password,
        $environment,
        $xApiKey = null
    ) {
        $client = new \Adyen\Client();

        $client->setApplicationName($applicationName);
        $client->setUsername($username);
        $client->setPassword($password);
        $client->setEnvironment($environment);
        $client->setXApiKey($xApiKey);
        $client->setInputType('json');

        $this->client = $client;
    }

    /**
     * @return Payment
     */
    public function getPaymentService()
    {
        return new Payment($this->client);
    }

    /**
     * @return Recurring
     */
    public function getRecurringService()
    {
        return new Recurring($this->client);
    }

    /**
     * @return Checkout
     * @throws \Adyen\AdyenException
     */
    public function getCheckoutService()
    {
        return new Checkout($this->client);
    }
}

