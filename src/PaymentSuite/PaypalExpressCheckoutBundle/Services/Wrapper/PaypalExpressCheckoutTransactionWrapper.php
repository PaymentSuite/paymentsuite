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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Services\Wrapper;

class PaypalExpressCheckoutTransactionWrapper
{
    private $user;
    private $password;
    private $signature;
    private $endpoint;
    private $debug;
    public $errors = array();
    public $response = null;

    public function __construct($user, $password, $signature, $endpoint, $debug = false)
    {
        $this->user = $user;
        $this->password = $password;
        $this->signature = $signature;
        $this->endpoint = $endpoint;
        if (true === $debug) {
            $this->endpoint = str_replace('sandbox.','', $this->endpoint);
        }
    }

    public function request($method, $params)
    {
        $params = array_merge($params, array(
                'METHOD' => $method,
                'VERSION' => '74.0',
                'USER'   => $this->user,
                'SIGNATURE' => $this->signature,
                'PWD'    => $this->password
        ));
        $params = http_build_query($params);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint,
            CURLOPT_POST=> 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => 1
        ));
        $response = curl_exec($curl);
        $responseArray = array();
        parse_str($response, $responseArray);
        if (curl_errno($curl)) {
            $this->errors = curl_error($curl);
            curl_close($curl);

            return false;
        } else {
            if ($responseArray['ACK'] == 'Success') {
                curl_close($curl);
                $this->reponse = $responseArray;
            } else {
                $this->errors = $responseArray;
                curl_close($curl);

                return false;
            }
        }
    }

    public function getToken()
    {
        return $this->response['TOKEN'];
    }

    public function getResponse()
    {
        return $this->response;
    }
}
