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

namespace PaymentSuite\AuthorizenetBundle\Services\Wrapper;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * Authorizenet transaction wrapper
 */
class AuthorizenetTransactionWrapper
{
    /**
     * @var string
     *
     * Endpoint for API
     */
    protected $endpoint;

    /**
     * Construct method for Authorize.Net transaction wrapper
     *
     * @param string  $endpointTest test endpoint
     * @param string  $endpointLive live endpoint
     * @param boolean $testMode     boolean to activate test or live mode
     */
    public function __construct($endpointTest, $endpointLive, $testMode)
    {
        $this->endpoint =($testMode) ? $endpointTest : $endpointLive;
    }

    /**
     * Create new Transaction with a set of params
     *
     * @param string $params Set of params
     *
     * @return array            Result of transaction
     * @throws PaymentException
     */
    public function create($params)
    {
        $chargeData = array();
        try {
            $request = curl_init($this->endpoint);
            curl_setopt($request, CURLOPT_HEADER, 0);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($request, CURLOPT_POSTFIELDS, $params);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            $postResponse = curl_exec($request);
            curl_close ($request);

            $chargeData = explode('|',$postResponse);
        } catch (\Exception $e) {
            throw new PaymentException();
        }

        return $chargeData;
    }
}
