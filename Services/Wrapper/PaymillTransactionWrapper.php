<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymillBundle\Services\Wrapper;

use Services_Paymill_Transactions;

/**
 * Paymill transaction wrapper
 */
class PaymillTransactionWrapper
{

    /**
     * @var Services_Paymill_Transactions
     * 
     * Paymill Transaction
     */
    private $paymillTransaction;


    /**
     * Construct method for paymill transaction wrapper
     *
     * @param string $privateKey Private key
     * @param string $publicKey  Public key
     */
    public function __construct($privateKey, $publicKey)
    {
        $this->paymillTransaction = new Services_Paymill_Transactions(

            $privateKey,
            $apiEndPoint
        );
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
        return $this
            ->paymillTransaction
            ->create($params);
    }
}