<?php
namespace Scastells\PayuBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;


/**
 * PayU manager
 */
class PayuManager
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
     * @var logger
     *
     */
    private $logger;


    /**
     * varg string
     */
    private $host;


    /**
     * @var string
     */
    private $login;


    /**
     * @var integer
     */
    private $key;


    /**
     * @var integer
     */
    private $accountId;


    /**
     * @var integer
     */
    private $merchantId;


    /**
     * @var boolean
     */
    private $modeTest;

    /**
     * @var JsonDeocde
     */
    private $decode;


    /**
     * @var JsonEncode
     */
    private $encode;

    /**
     * Construct method for pagosonline manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param $host
     * @param $logger
     * @param $login
     * @param $key
     * @param $accountId
     * @param $merchantId
     * @param $modeTest
     * @param $decode
     * @param $enocde
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge,
                                $host, $logger, $login, $key, $accountId, $merchantId, $modeTest, $decode, $enocde)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->host = $host;
        $this->logger = $logger;
        $this->login = $login;
        $this->key = $key;
        $this->accountId = $accountId;
        $this->merchantId = $merchantId;
        $this->modeTest = $modeTest;
        $this->decoder = $decode;
    }


    /**
     * Tries to process a payment through Pagosonline
     *
     * @param PagosonlineMethod $paymentMethod Payment method
     * @param float         $amount        Amount
     *
     * @return PagosonlineManager Self object
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     * @throws PaymentException
     */
    public function processPayment(PagosonlineMethod $paymentMethod, $amount)
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
        $merchant = array(
            'apiLogin'  => $this->login,
            'apiKey'    => $this->key
        );

        $additionalValue = array(
            'value'     => $amount,
            'currency'  => $this->paymentBridge->getCurrency()
        );

        $buyer = array(
            'fullName'      => $extraData['customer_firstname'].' '.$extraData['customer_lastname'],
            'emailAddress'  => $extraData['customer_email'],
            'contactPhone'  => $extraData['customer_phone']
        );

        $additionalValues = array(
            'TX_VALUE' => $additionalValue
        );
        $referenceCode = '';
        $signature = md5($this->key.'~'.$this->merchantId.'~'.$referenceCode.'~'.$amount.'~'.$this->paymentBridge->getCurrency());
        $order = array(
            'accountId'         => $this->accountId,
            'referenceCode'     => $referenceCode,
            'description'       => $this->paymentBridge->getOrderDescription(),
            'notifyUrl'         => '',
            'language'          => '',
            'signature'         => $signature,
            'buyer'             => $buyer,
            'additionalvValues' => $additionalValues
        );

        $extraParameters = array('INSTALLMENTS_NUMBER' => 1);

        $transaction = array(
            'order'             => $order,
            'type'              => 'AUTHORIZATION_AND_CAPTURE',
            'paymentMethod'     => $paymentMethod->getCardType(),
            'source'            => 'WEB',
            'extraParameters'   => $extraParameters,
            'deviceSessionId'   => '',
        );

        $params = array(
            'language'      => '',
            'comand'        => 'SUBMIT_TRANSACTION',
            'test'          => $this->modeTest,
            'merchant'      => $merchant,
            'transaction'   => $transaction
        );

        $encoder = $this->enocder->encode($params, 'JSON');

        $this->logger->addInfo($paymentMethod->getPaymentName().'processPayment', $params);

        $this->processTransaction($encoder, $paymentMethod);
        return $this;
    }


    /**
     * Given a payU response, as an array, prform desired operations
     *
     * @param          $encoder
     * @param PagosonlineMethod $paymentMethod Payment method
     *
     * @return PagosonlineManager Self object
     *
     * @throws PaymentException
     */
    private function processTransaction($encoder, PagosonlineMethod $paymentMethod)
    {

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        //curl connection
        $ch = curl_init($this->host);//cambiarlo por host_report
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/".json,
            "Content-Length: ".strlen($encoder)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encoder);
        $buffer = curl_exec($ch);
        $result = $this->decoder->decode($buffer);

        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransaction', get_object_vars($result));

        if (is_object($result) || $result->code == "SUCCESS") {
            switch ($result->transactionResponse->state) {
                case 'APPROVED':
                    $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);
                    break;
                case 'PENDING':
                    if (is_object($result->transactionResponse->extraParameters) &&
                        isset($result->transactionResponse->extraParameters->VISANET_PE_URL)) {
                        return array(
                            $result->transactionResponse->trazabilityCode,
                            $result->transactionResponse->extraParameters->VISANET_PE_URL
                        );
                    }
                    break;
                case 'ERROR':
                case 'DECLINED':
                case 'EXPIRED':
                    $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
                    throw new PaymentException;
                    break;
            }
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