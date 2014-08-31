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

namespace PaymentSuite\PayUBundle\Factory;

use PaymentSuite\PayuBundle\Model\AuthorizationAndCaptureTransaction;
use PaymentSuite\PayuBundle\Model\CaptureRefundVoidTransaction;
use PaymentSuite\PayuBundle\Model\PayuTransaction;
use PaymentSuite\PayuBundle\PayuTransactionTypes;

/**
 * Class PayuTransactionFactory
 */
class PayuTransactionFactory
{
    /**
     * Construct method
     *
     */
    public function __construct()
    {
    }

    /**
     * Creates an instance of PayuTransaction model
     *
     * @param string $type Transaction type
     *
     * @return PayuTransaction Empty model
     */
    public function create($type)
    {
        switch ($type) {
            case PayuTransactionTypes::TYPE_AUTHORIZATION:
            case PayuTransactionTypes::TYPE_AUTHORIZATION_AND_CAPTURE:
                $transaction = new AuthorizationAndCaptureTransaction();
                break;
            case PayuTransactionTypes::TYPE_CAPTURE;
            case PayuTransactionTypes::TYPE_REFUND:
            case PayuTransactionTypes::TYPE_VOID:
                $transaction = new CaptureRefundVoidTransaction();
                break;
            default:
                throw new \Exception('Transaction type '.$type.' not supported');
                break;
        }
        $transaction->setType($type);

        return $transaction;
    }
}
