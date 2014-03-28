<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Factory;

use PaymentSuite\PayuBundle\Model\GetPaymentMethodsRequest;
use PaymentSuite\PayuBundle\Model\PayuRequest;
use PaymentSuite\PayuBundle\Model\PingRequest;
use PaymentSuite\PayuBundle\Model\SubmitTransactionRequest;
use PaymentSuite\PayuBundle\PayuRequestTypes;

/**
 * Class PayuRequestFactory
 */
class PayuRequestFactory
{
    /**
     * @var string
     *
     * language
     */
    protected $language;

    /**
     * @var boolean
     *
     * test
     */
    protected $test;

    /**
     * @var MerchantFactory
     *
     * merchantFactory
     */
    protected $merchantFactory;

    /**
     * Construct method
     *
     * @param string          $language        Language used on requests
     * @param boolean         $test            Test request or not
     * @param MerchantFactory $merchantFactory Merchant factory
     */
    public function __construct($language, $test, MerchantFactory $merchantFactory)
    {
        $this->language = $language;
        $this->test = $test;
        $this->merchantFactory = $merchantFactory;
    }

    /**
     * Creates an instance of PayuRequest model
     *
     * @param string $type Request type
     *
     * @return PayuRequest Empty model
     */
    public function create($type)
    {
        switch ($type) {
            case PayuRequestTypes::TYPE_PING:
                $request = new PingRequest();
                break;
            case PayuRequestTypes::TYPE_GET_PAYMENT_METHODS:
                $request = new GetPaymentMethodsRequest();
                break;
            case PayuRequestTypes::TYPE_SUBMIT_TRANSACTION:
                $request = new SubmitTransactionRequest();
                break;
            default:
                throw new \Exception('Request type '.$type.' not supported');
                break;
        }
        $request->setLanguage($this->language);
        $request->setTest($this->test);
        $request->setCommand($type);
        $request->setMerchant($this->merchantFactory->create());

        return $request;
    }
}
