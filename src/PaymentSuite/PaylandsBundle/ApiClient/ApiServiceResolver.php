<?php

namespace PaymentSuite\PaylandsBundle\ApiClient;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class ApiServiceResolver.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class ApiServiceResolver
{
    /**
     * @var array
     */
    protected $services;

    /**
     * @var PaymentBridgeInterface
     */
    protected $paymentBridge;

    /**
     * ApiServiceResolver constructor.
     *
     * @param PaymentBridgeInterface $paymentBridge
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;

        $this->services = [];
    }

    /**
     * Adds a new service ID for a given currency ISO code.
     *
     * @param $currency
     * @param $service
     *
     * @return $this Self instance
     */
    public function addService($currency, $service)
    {
        $this->services[$currency] = $service;

        return $this;
    }

    /**
     * Gets the available service ID for currency indicated by PaymentBridge.
     *
     * @return string Service ID for the currency
     */
    public function getService()
    {
        $currency = $this->paymentBridge->getCurrency();

        if (key_exists($currency, $this->services)) {
            return $this->services[$currency];
        }

        return '';
    }
}
