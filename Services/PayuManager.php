<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Services;

use JMS\Serializer\Serializer;
use PaymentSuite\PayuBundle\Factory\OrderFactory;
use PaymentSuite\PayuBundle\Factory\PayuRequestFactory;
use PaymentSuite\PayuBundle\Factory\PayuTransactionFactory;
use PaymentSuite\PayuBundle\Model\AuthorizationAndCaptureTransaction;
use PaymentSuite\PayuBundle\Model\Abstracts\PayuRequest;
use PaymentSuite\PayuBundle\PayuRequestTypes;
use PaymentSuite\PayuBundle\PayuTransactionTypes;

/**
 * PayuManager
 */
class PayuManager
{
    /**
     * @var string
     *
     * merchantKey
     */
    protected $merchantKey;

    /**
     * @var string
     *
     * merchantId
     */
    protected $merchantId;

    /**
     * @var Serializer
     *
     * serializer
     */
    protected $serializer;

    /**
     * Construct method
     *
     * @param string     $merchantKey Merchant Key
     * @param string     $merchantId  Merchant Id
     * @param Serializer $serializer  Serializer
     */
    public function __construct($merchantKey, $merchantId, Serializer $serializer)
    {
        $this->serializer = $serializer;
        $this->merchantKey = $merchantKey;
        $this->merchantId = $merchantId;
    }

    /**
     * Process Payment Request
     *
     * @param PayuRequest $request Payu Request
     *
     * @return PayuResponse Payu Response
     */
    public function processAuthorizationAndCapture(PayuRequest $request)
    {
        die(var_dump($this->serializer->serialize($request, 'json')));

        return $response;
    }

    /**
     * Calculate signature
     *
     * @return string Transaction signature
     */
    public function getSignature($reference, $amount, $currency)
    {
        $signature = md5($this->merchantKey.'~'.$this->merchantId.'~'.$reference.'~'.$amount.'~'.$currency);

        return $signature;
    }
}
