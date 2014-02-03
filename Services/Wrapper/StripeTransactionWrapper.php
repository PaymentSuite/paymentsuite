<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package StripeBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\StripeBundle\Services\Wrapper;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use Stripe;
use Stripe_Charge;

/**
 * Stripe transaction wrapper
 */
class StripeTransactionWrapper
{

    /**
     * @var string
     *
     * Private key
     */
    protected $privateKey;


    /**
     * Construct method for stripe transaction wrapper
     *
     * @param string $privateKey Private key
     */
    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }


    /**
     * Create new Transaction with a set of params
     *
     * @param array $params Set of params
     *
     * @return array Result of transaction
     * @throws PaymentException
     */
    public function create(array $params)
    {
        try {
            Stripe::setApiKey($this->privateKey);
            $charge = Stripe_Charge::create($params);
            $chargeData = json_decode($charge, true);
        } catch (\Exception $e) {
            throw new PaymentException;
        }

        return $chargeData;
    }
}