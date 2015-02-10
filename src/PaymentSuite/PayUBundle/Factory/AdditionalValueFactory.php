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

namespace PaymentSuite\PayUBundle\Factory;

use PaymentSuite\PayuBundle\Model\AdditionalValue;

/**
 * Class AdditionalValueFactory
 */
class AdditionalValueFactory
{
    /**
     * Creates an instance of AdditionalValue model
     *
     * @return AdditionalValue Empty model
     */
    public function create()
    {
        $additionalValue = new AdditionalValue();

        return $additionalValue;
    }
}
