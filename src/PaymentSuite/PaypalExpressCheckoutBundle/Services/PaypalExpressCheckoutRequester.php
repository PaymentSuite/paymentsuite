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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Services\Wrapper;

use PaymentSuite\PaymentCoreBundle\ValueObject\PaypalExpressCheckoutResponse;

/**
 * Class PaypalExpressCheckoutRequester
 */
class PaypalExpressCheckoutRequester
{
    /**
     * @var string
     *
     * user
     */
    private $user;

    /**
     * @var string
     *
     * password
     */
    private $password;

    /**
     * @var string
     *
     * signature
     */
    private $signature;

    /**
     * @var mixed
     *
     * endpoint
     */
    private $endpoint;

    /**
     * @param string $user      User
     * @param string $password  Password
     * @param string $signature Signature
     * @param string $endpoint  Endpoint
     * @param bool   $debug     Debug mode
     */
    public function __construct(
        $user,
        $password,
        $signature,
        $endpoint,
        $debug = false
    ) {
        $this->user = $user;
        $this->password = $password;
        $this->signature = $signature;
        $this->endpoint = $endpoint;

        if (true === $debug) {
            $this->endpoint = str_replace(
                'sandbox.',
                '',
                $this->endpoint
            );
        }
    }

    /**
     * Do a request and store locally the response
     *
     * @param string $method Method
     * @param array  $params Params
     *
     * @return PaypalExpressCheckoutResponse Response
     */
    public function request($method, array $params)
    {
        $params = array_merge($params, [
            'METHOD'    => $method,
            'VERSION'   => '74.0',
            'USER'      => $this->user,
            'SIGNATURE' => $this->signature,
            'PWD'       => $this->password,
        ]);
        $params = http_build_query($params);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->endpoint,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $params,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE        => 1,
        ]);
        $response = curl_exec($curl);
        $responseArray = [];
        parse_str($response, $responseArray);
        if (curl_errno($curl)) {
            $apiResponse = new PaypalExpressCheckoutResponse(
                [],
                [curl_error($curl)]
            );
            curl_close($curl);

            return $apiResponse;
        }

        curl_close($curl);

        return ($responseArray['ACK'] == 'Success')
            ? new PaypalExpressCheckoutResponse(
                $responseArray,
                []
            )
            : new PaypalExpressCheckoutResponse(
                [],
                $responseArray
            );
    }
}
