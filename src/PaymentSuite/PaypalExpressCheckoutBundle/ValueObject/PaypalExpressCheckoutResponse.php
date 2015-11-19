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

namespace PaymentSuite\PaypalExpressCheckoutBundle\ValueObject;

/**
 * Class PaypalExpressCheckoutResponse
 */
class PaypalExpressCheckoutResponse
{
    /**
     * @var array
     *
     * Response
     */
    private $response;

    /**
     * @var array
     *
     * Errors
     */
    private $errors;

    /**
     * Construct
     *
     * @param array $response Response
     * @param array $errors   Errors
     */
    public function __construct(
        array $response,
        array $errors
    ) {
        $this->response = $response;
        $this->errors = $errors;
    }

    /**
     * Get token
     *
     * @return string|null Token
     */
    public function getToken()
    {
        return isset($this->response['TOKEN'])
            ? $this->response['TOKEN']
            : null;
    }

    /**
     * Get checkout status
     *
     * @return string|null Checkout status
     */
    public function getCheckoutStatus()
    {
        return isset($this->response['CHECKOUTSTATUS'])
            ? $this->response['CHECKOUTSTATUS']
            : null;
    }

    /**
     * Get response
     *
     * @return array response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get errors
     *
     * @return array errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
