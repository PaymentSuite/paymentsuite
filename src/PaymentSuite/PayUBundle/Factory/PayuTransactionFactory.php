<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
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
