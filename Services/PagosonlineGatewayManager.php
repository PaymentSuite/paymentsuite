<?php

namespace PaymentSuite\PagosonlineGatewayBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PagosonlineCommBundle\Services\PagosonlineCommManager;
use PaymentSuite\PagosonlineGatewayBundle\PagosonlineGatewayMethod;

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
     * @var integer
     */
    private $accountId;

    /**
     * Construct method for pagosonline manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param $accountId
     * @param PagosonlineCommManager $pagosonlineComm
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $accountId, PagosonlineCommManager $pagosonlineComm)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
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

        /**
         * if pagosonline return code 15 o 9994 the order status is pending
         */

        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

        if (in_array($statusTransactionWS->codigoRespuesta, array('15','9994'))) {

            //payment is still pending nothing to do

        } elseif ($statusTransactionWS->codigoRespuesta == 1) {

            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        } else {

            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
        }
    }

} 