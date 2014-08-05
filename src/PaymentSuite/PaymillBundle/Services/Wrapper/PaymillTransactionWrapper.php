<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author  Marc Morera <yuhu@mmoreram.com>
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaymillBundle\Services\Wrapper;

use Paymill\Models\Response\Transaction;
use Paymill\Request;
use Paymill\Services\PaymillException;

/**
 * Paymill transaction wrapper
 */
class PaymillTransactionWrapper
{
    /**
     * @var string Paymill private API key
     */
    private $apiKey;

    /**
     * Construct method for paymill transaction wrapper
     *
     * @param string $privateKey Private key
     */
    public function __construct($privateKey)
    {
        $this->apiKey = $privateKey;
    }

    /**
     * Create new Transaction with a set of params
     *
     * @param string $amount      amount as int (ex: 4200 for 42.00)
     * @param string $currency    currency code (EUR, USD...)
     * @param string $token       transaction token
     * @param string $description transaction description (optional, default "")
     *
     * @return Transaction
     *
     * @throws PaymillException if transaction creation fails
     */
    public function create($amount, $currency, $token, $description = "")
    {
        $service = new Request($this->apiKey);
        $transaction = new Transaction();
        $transaction
            ->setAmount($amount)
            ->setCurrency($currency)
            ->setToken($token)
            ->setDescription($description);

        /**
         * @var Transaction $response
         */
        $response = $service->create($transaction);

        return $response;
    }
}
