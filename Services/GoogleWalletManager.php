<?php

/**
 * GoogleWalletBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package GoogleWalletBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\GoogleWalletBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;

use dpcat237\GoogleWalletBundle\Entity\Payload;
use dpcat237\GoogleWalletBundle\Helper\JWTHelper;
use dpcat237\GoogleWalletBundle\GoogleWalletMethod;

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

        $payload = new Payload();
        $payload->SetIssuedAt(time());
        $payload->SetExpiration(time()+3600);
        $payload->AddProperty("name", $extraData['order_name']);
        $payload->AddProperty("description", $extraData['order_description']);
        $payload->AddProperty("price", $this->paymentBridge->getAmount());
        $payload->AddProperty("currencyCode", $this->paymentBridge->getCurrency());

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

            throw new PaymentException;
        }

        return $this;
    }
}