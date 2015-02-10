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

namespace PaymentSuite\StripeBundle\Services\Wrapper;

use Stripe;
use Stripe_Charge;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

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
     * @return array            Result of transaction
     * @throws PaymentException
     */
    public function create(array $params)
    {
        try {
            Stripe::setApiKey($this->privateKey);
            $charge = Stripe_Charge::create($params);
            $chargeData = json_decode($charge, true);
        } catch (\Exception $e) {
            throw new PaymentException();
        }

        return $chargeData;
    }
}
