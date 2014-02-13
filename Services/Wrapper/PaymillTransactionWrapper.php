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

namespace PaymentSuite\PaymillBundle\Services\Wrapper;

use Paymill\Request;
use Paymill\Models\Request\Transaction;
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
     * @param string $privateKey  Private key
     * @param string $apiEndpoint Api endpoint
     */
    public function __construct($privateKey, $apiEndpoint)
    {
        $this->apiKey = $privateKey;
    }

    /**
     * Create new Transaction with a set of params
     *
     * @param $amount      amount as int (ex: 4200 for 42.00)
     * @param $currency    currency code (EUR, USD...)
     * @param $token       transaction token
     * @param $description transaction description (optional, default "")
     *
     * @return mixed
     *
     * @throws PaymillException if transaction creation fails
     */
    public function create($amount, $currency, $token, $description = "")
    {
        $service = new Request($this->apiKey);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setCurrency($currency)
            ->setToken($token)
            ->setDescription($description);

        $response = $service->create($transaction);

        //response here has to be a Transaction object
        return $response;
    }
}
