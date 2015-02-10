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

namespace PaymentSuite\GoogleWalletBundle\Services;

use PaymentSuite\GoogleWalletBundle\Entity\Payload;
use PaymentSuite\GoogleWalletBundle\GoogleWalletMethod;
use PaymentSuite\GoogleWalletBundle\Helper\JWTHelper;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

/**
 * GoogleWallet manager
 */
class GoogleWalletManager
{
    /**
     * @var integer
     *
     */
    private $merchantId;

    /**
     * @var PaymentEventDispatcher
     *
     */
    protected $paymentEventDispatcher;

    /**
     * @var PaymentBridgeInterface
     *
     */
    protected $paymentBridge;

    /**
     * @var string
     *
     */
    private $secretKey;

    /**
     * Construct method for googlewallet manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param integer                $merchantId             Merchant ID or iss
     * @param string                 $secretKey              Merchant secret key
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $merchantId, $secretKey)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
    }

    /**
     * Generate token to make the payment request
     *
     * @return string
     */
    public function generateToken()
    {
        $extraData = $this->paymentBridge->getExtraData();
        $cartAmount = (float) number_format(($this->paymentBridge->getAmount() / 100), 2, '.', '');

        $payload = new Payload();
        $payload->setIssuedAt(time());
        $payload->setExpiration(time()+3600);
        $payload->addProperty("name", $extraData['order_name']);
        $payload->addProperty("description", $extraData['order_description']);
        $payload->addProperty("price", $cartAmount);
        $payload->addProperty("currencyCode", $this->paymentBridge->getCurrency());

        $token = $payload->CreatePayload($this->merchantId);
        $jwtToken = JWTHelper::encode($token, $this->secretKey);

        $paymentMethod = new GoogleWalletMethod();
        $paymentMethod->setApiToken($jwtToken);
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

        return $jwtToken;
    }

    /**
     * Collect callback data after payment process
     *
     * @param array $response Response data
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentException
     *
     * @return GoogleWalletManager Self object
     */
    public function processPayment($response)
    {
        $paymentMethod = new GoogleWalletMethod();
        $paymentMethod->setTransactionResponse($response);

        if (in_array('orderId', $response)) {
            $paymentMethod
                ->setTransactionId($response['orderId'])
                ->setTransactionStatus('paid');
            $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);
            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);
        } else {
            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);

            throw new PaymentException();
        }

        return $this;
    }
}
