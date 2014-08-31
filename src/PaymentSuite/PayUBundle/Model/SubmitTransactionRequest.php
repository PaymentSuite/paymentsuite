<?php

/**
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

use PaymentSuite\PayuBundle\Model\Abstracts\PayuRequest;
use PaymentSuite\PayuBundle\Model\Abstracts\PayuTransaction;

/**
 * SubmitTransaction Request Model
 */
class SubmitTransactionRequest extends PayuRequest
{
    /**
     * @var PayuTransaction
     *
     * transaction
     */
    protected $transaction;

    /**
     * Sets Transaction
     *
     * @param PayuTransaction $transaction Transaction
     *
     * @return SubmitTransactionRequest Self object
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get Transaction
     *
     * @return PayuTransaction Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
