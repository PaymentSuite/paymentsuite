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

use PaymentSuite\StripeBundle\StripeMethod;

/**
 * Class StripeMethodFactory.
 */
class StripeMethodFactory
{
    /**
     * Given some data, creates a StripeMethod object.
     *
     * @param string $apiToken                  Api token
     * @param string $creditCardNumber          Credit card number
     * @param string $creditCardOwner           Credit card owner
     * @param string $creditCardExpirationYear  Credit card expiration year
     * @param string $creditCardExpirationMonth Credit card expiration month
     * @param string $creditCardSecurity        Credit card security
     *
     * @return StripeMethod StripeMethod instance
     */
    public function create(
        $apiToken,
        $creditCardNumber,
        $creditCardOwner,
        $creditCardExpirationYear,
        $creditCardExpirationMonth,
        $creditCardSecurity
    ) {
        return new StripeMethod(
            $apiToken,
            $creditCardNumber,
            $creditCardOwner,
            $creditCardExpirationYear,
            $creditCardExpirationMonth,
            $creditCardSecurity
        );
    }
}
