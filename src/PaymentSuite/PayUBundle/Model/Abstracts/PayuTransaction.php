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

namespace PaymentSuite\PayUBundle\Model\Abstracts;

/**
 * Abstract Model class for transaction models
 */
abstract class PayuTransaction
{
    /**
     * @var string
     *
     * type
     */
    protected $type;

    /**
     * Sets Type
     *
     * @param string $type Type
     *
     * @return PayuTransaction Self object
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Type
     *
     * @return string Type
     */
    public function getType()
    {
        return $this->type;
    }
}
