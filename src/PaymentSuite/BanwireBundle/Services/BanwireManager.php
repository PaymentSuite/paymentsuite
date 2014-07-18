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

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\BanwireBundle\BanwireMethod;

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
     * Construct method for banwire manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param string                 $user                   User
     * @param $api                   $api
     * @param $logger                $logger
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $user, $api)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->user = $user;
        $this->api = $api;
    }

    /**
     * Tries to process a payment through Banwire
     *
     * @param BanwireMethod $paymentMethod Payment method
     * @param float         $amount        Amount
     *
     * @return BanwireManager Self object
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     * @throws PaymentException
     */
    public function processPayment(BanwireMethod $paymentMethod, $amount)
    {
        /// first check that amounts are the same
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
        //params to send banwire api

        $carExp = substr($paymentMethod->getCardExpYear(), -2);
        $params = array (
            'response_format'   => 'JSON',
            'user'              => $this->user,
            'reference'         => $this->paymentBridge->getOrderId(). '#'.  date('Ymdhis'),
            'currency'          => $this->paymentBridge->getCurrency(),
            'ammount'           => number_format($this->paymentBridge->getAmount(), 2) * 100,
            'concept'           => $this->paymentBridge->getOrderDescription(),
            'card_num'          => $paymentMethod->getCardNum(),
            'card_name'         => $paymentMethod->getCardName(),
            'card_type'         => $paymentMethod->getCardType(),
            'card_exp'          => $paymentMethod->getCardExpMonth().'/'.$carExp,
            'card_ccv2'         => $paymentMethod->getCardSecurity(),
            'address'           => $extraData['correspondence_address'],
            'post_code'         => $extraData['customer_postal_code'],
            'phone'             => $extraData['customer_phone'],
            'mail'             => $extraData['customer_email']
        );

        $host = $this->api;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; WINDOWS; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);//cambiar el valor a 2 en prod??
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        if (defined('CURLOPT_ENCODING')) curl_setopt($ch, CURLOPT_ENCODING, "");
        $responseApi = curl_exec($ch);

        $this->processTransaction($responseApi, $paymentMethod);

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
    private function processTransaction($responseApi, BanwireMethod $paymentMethod)
    {
        $banwireParams = json_decode($responseApi);

        if (isset($banwireParams->order_id)) {

            $paymentMethod->setBanwireTransactionId($banwireParams->order_id);
        }

        if (isset($banwireParams->referencia)) {

            $paymentMethod->setBanwireReference($banwireParams->referencia);
        }

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

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
