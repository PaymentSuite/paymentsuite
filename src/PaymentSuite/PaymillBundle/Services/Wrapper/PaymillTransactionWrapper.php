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

namespace PaymentSuite\PaymillBundle\Services\Wrapper;

use Paymill\Models\Request\Transaction as RequestTransaction;
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
     * @return \Paymill\Models\Response\Base
     *
     * @throws PaymillException if transaction creation fails
     */
    public function create($amount, $currency, $token, $description = "")
    {
        $service = new Request($this->apiKey);
        $transaction = new RequestTransaction();
        $transaction
            ->setAmount($amount)
            ->setCurrency($currency)
            ->setToken($token)
            ->setDescription($description);

        /**
         * @var \Paymill\Models\Response\Base $response
         */
        $response = $service->create($transaction);

        return $response;
    }
}
