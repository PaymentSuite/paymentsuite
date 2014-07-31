<?php

namespace PaymentSuite\PagosOnlineGatewayBundle\Services;

use PaymentSuite\PagosonlineCommBundle\Services\PagosonlineCommManager;
use PaymentSuite\PagosonlineGatewayBundle\PagosonlineGatewayMethod;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

class PagosonlineGatewayManager
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
     * @var PagosonlineConnManager
     */
    protected $pagosonlineComm;

    /**
     * @var logger
     *
     */
    private $logger;

    /**
     * @var integer
     */
    private $accountId;

    /**
     * Construct method for pagosonline manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param $logger
     * @param $accountId
     * @param PagosonlineCommManager $pagosonlineComm
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $logger, $accountId, PagosonlineCommManager $pagosonlineComm)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->logger = $logger;
        $this->accountId = $accountId;
        $this->pagosonlineComm = $pagosonlineComm;
    }

    public function checkTransactionStatus($transactionId)
    {
        //All information of transaction response
        $statusTransactionWS = $this->pagosonlineComm->consultarEstadoTransaccion($this->accountId, $transactionId);

        $paymentMethod = new PagosonlineGatewayMethod();
        $paymentMethod->setPagosonlineGatewayTransactionId($statusTransactionWS->transaccionId);
        $paymentMethod->setStatus($statusTransactionWS->estadoId);
        $paymentMethod->setReference($statusTransactionWS->referencia);
        $paymentMethod->setAmount($statusTransactionWS->valor);

        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransactionCheck', get_object_vars($statusTransactionWS));

        /**
         * if pagosonline return code 15 o 9994 the order status is pending
         */
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

        if ($statusTransactionWS->codigoRespuesta == 1) {

            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        } elseif (!in_array($statusTransactionWS->codigoRespuesta, array(15, 9994))) { //status 15 or 9994 payment is still in pending nothing to do

            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
        }
    }

}
