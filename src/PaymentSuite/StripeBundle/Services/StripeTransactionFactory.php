<?php

/*
 * This file is part of the PaymentSuite package.
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
use Stripe\Charge;
use Stripe\Stripe;

/**
 * class StripeTransactionFactory
 */
class StripeTransactionFactory
{
    /**
     * @var string
     *
     * Private key
     */
    private $privateKey;

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
     */
    public function create(array $params)
    {
        try {
            Stripe::setApiKey($this->privateKey);
            $chargeData = Charge::create($params);
        } catch (Exception $e) {
            // The way to get to 'notifyPaymentOrderFail'
            return [
                'paid' => 0,
            ];
        }

        return $chargeData;
    }
}
