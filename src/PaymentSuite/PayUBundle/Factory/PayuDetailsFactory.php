<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Factory;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuDetails;
use PaymentSuite\PayuBundle\Model\OrderDetailByReferenceCodeDetailsDetails;
use PaymentSuite\PayuBundle\Model\OrderDetailDetails;
use PaymentSuite\PayuBundle\Model\TransactionResponseDetailDetails;
use PaymentSuite\PayuBundle\PayuDetailsTypes;

/**
 * Class PayuDetailsFactory
 */
class PayuDetailsFactory
{
    /**
     * Construct method
     *
     */
    public function __construct()
    {
    }

    /**
     * Creates an instance of PayuDetails model
     *
     * @param string $type Details type
     *
     * @return PayuDetails Empty model
     */
    public function create($type)
    {
        switch ($type) {
            case PayuDetailsTypes::TYPE_ORDER_DETAIL:
                $details = new OrderDetailDetails();
                break;
            case PayuDetailsTypes::TYPE_ORDER_DETAIL_BY_REFERENCE_CODE:
                $details = new OrderDetailByReferenceCodeDetailsDetails();
                break;
            case PayuDetailsTypes::TYPE_TRANSACTION_RESPONSE_DETAIL:
                $details = new TransactionResponseDetailDetails();
                break;
            default:
                throw new \Exception('Details type '.$type.' not supported');
                break;
        }

        return $details;
    }
}
