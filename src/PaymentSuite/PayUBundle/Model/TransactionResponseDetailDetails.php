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

namespace PaymentSuite\PayUBundle\Model;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuDetails;

/**
 * TransactionResponseDetail Details Model
 */
class TransactionResponseDetailDetails extends PayuDetails
{
    /**
     * @var string
     *
     * transactionId
     */
    protected $transactionId;

    /**
     * Sets TransactionId
     *
     * @param string $transactionId TransactionId
     *
     * @return TransactionResponseDetailDetails Self object
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get TransactionId
     *
     * @return string TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
