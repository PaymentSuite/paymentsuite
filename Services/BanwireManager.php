<?php

/**
 * BanwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BanwireBundle
 *
 * Marc Morera 2013
 */


namespace PaymentSuite\BanwireBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\BanwireBundle\BanwireMethod;
use Buzz\Browser as Buzz;

/**
 * Banwire manager
 */
class BanwireManager
{

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;



    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;

    /**
     * @var string
     *
     * user banwire
     */
    private $user;


    /**
     * @var string
     *
     * url api banwire
     */
    private $api;


    /**
     * @var logger
     *
     */
    private $logger;


    /**
     * Construct method for banwire manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param string                 $user              User
     * @param $api                   $api
     * @param $logger                $logger
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $user, $api, $logger)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->user = $user;
        $this->api = $api;
        $this->logger = $logger;
    }


    /**
     * Tries to process a payment through Banwire
     *
     * @param Buzz
     * @param BanwireMethod $paymentMethod Payment method
     * @param float         $amount        Amount
     *
     * @return BanwireManager Self object
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     * @throws PaymentException
     */
    public function processPayment(Buzz $buzz, BanwireMethod $paymentMethod, $amount)
    {
        $paymentBridgeAmount = (float) $this->paymentBridge->getAmount() * 100;

        /**
         * If both amounts are different, execute Exception
         */
        if (abs($amount - $paymentBridgeAmount) > 0.00001) {

            throw new PaymentAmountsNotMatchException;
        }

        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paymentMethod);

        $extraData = $this->paymentBridge->getExtraData();

        /**
         * params to send to banwire api
         */
        $cardExpYear = substr($paymentMethod->getCardExpYear(), -2);
        $cartExpMonth = $paymentMethod->getCardExpMonth();
        $cartExp = $cartExpMonth . '/' . $cardExpYear;
        $amout = number_format($this->paymentBridge->getAmount(), 2) * 100;
        $reference = $this->paymentBridge->getOrderId(). '#'.  date('Ymdhis');

        $params = array (
            'response_format'   => 'JSON',
            'user'              => $this->user,
            'reference'         => $reference,
            'currency'          => $this->paymentBridge->getCurrency(),
            'ammount'           => $amount,
            'concept'           => $this->paymentBridge->getOrderDescription(),
            'card_num'          => $paymentMethod->getCardNum(),
            'card_name'         => $paymentMethod->getCardName(),
            'card_type'         => $paymentMethod->getCardType(),
            'card_exp'          => $cartExp,
            'card_ccv2'         => $paymentMethod->getCardSecurity(),
            'address'           => $extraData['correspondence_address'],
            'post_code'         => $extraData['customer_postal_code'],
            'phone'             => $extraData['customer_phone'],
            'mail'              => $extraData['customer_email']
        );

        $client = $this->buzz->getClient();
        $client->setOption(CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; WINDOWS; .NET CLR 1.1.4322)');
        $client->setOption(CURLOPT_MAXREDIRS, 10);
        $client->setOption(CURLOPT_SSL_VERIFYHOST, 0);//cambiar el valor a 2 en prod??
        $client->setOption(CURLOPT_SSL_VERIFYPEER, 0);
        $client->setOption(CURLOPT_RETURNTRANSFER, 1);
        $client->setOption(CURLOPT_FOLLOWLOCATION, 1);
        $client->setOption(CURLOPT_TIMEOUT, 30);
        $client->setOption(CURLOPT_HEADER, 0);

        if (defined('CURLOPT_ENCODING')) {

            $client->setOption(CURLOPT_ENCODING, '');
        }

        $apiResponse = $buzz
            ->post($this->api, $params, http_build_query($params))
            ->getContent();

        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransaction Request', $params);
        $this->processTransaction($apiResponse, $paymentMethod);

        return $this;
    }


    /**
     * Given a paymillTransaction response, as an array, prform desired operations
     *
     * @param array         $apiResponse   Api response
     * @param BanwireMethod $paymentMethod Payment method
     *
     * @return BanwireManager Self object
     *
     * @throws PaymentException
     */
    private function processTransaction($apiResponse, BanwireMethod $paymentMethod)
    {
        $banwireParams = json_decode($apiResponse);
        $paymentMethod->setBanwireTransactionId($banwireParams->order_id);
        $paymentMethod->setBanwireReference($banwireParams->referencia);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);


        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransaction Response', get_object_vars($banwireParams));

       if ($banwireParams->response == 'ok') {

            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        } else {

            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
            throw new PaymentException;
        }

        /**
         * Log the response of gateway
         */
        return $this;
    }
}