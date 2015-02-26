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

namespace PaymentSuite\PagosOnlineBundle\Services;

use PaymentSuite\PagosonlineBundle\PagosonlineMethod;
use PaymentSuite\PagosonlineCommBundle\Services\PagosonlineCommManager;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

/**
 * Pagosonline manager
 */
class PagosonlineManager
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

    /**
     * Tries to process a payment through Pagosonline
     *
     * @param PagosonlineMethod $paymentMethod Payment method
     * @param float             $amount        Amount
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
        $paymentBridgeAmount = (float) $this->paymentBridge->getAmount();
        /**
         * If both amounts are different, execute Exception
         */
        if (abs($amount - $paymentBridgeAmount) > 0.00001) {

            throw new PaymentAmountsNotMatchException();
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

            throw new PaymentOrderNotFoundException();
        }

        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paymentMethod);

        $extraData = $this->paymentBridge->getExtraData();

        $object_ws = new \stdClass();
        $object_ws->cuentaId = $this->accountId;
        $object_ws->referencia = $this->paymentBridge->getOrderId(). '#'.  date('Ymdhis');
        $object_ws->descripcion = $this->paymentBridge->getOrderDescription();
        $object_ws->valor = $this->paymentBridge->getAmount();
        $object_ws->iva = $extraData['vat'];
        $object_ws->baseDevolucionIva = $extraData['refund_vat'];
        $object_ws->isoMoneda4217 = $this->paymentBridge->getCurrency();
        $object_ws->numeroCuotas = $paymentMethod->getCardQuota();
        $object_ws->nombreComprador = $extraData['customer_firstname'].$extraData['customer_lastname'];
        $object_ws->emailComprador = $extraData['customer_email'];
        $object_ws->franquicia = $paymentMethod->getCardType();
        $object_ws->numero = $paymentMethod->getCardNum();
        $object_ws->codigoSeguridad = $paymentMethod->getCardSecurity();
        $object_ws->nombreTarjetaHabiente = $paymentMethod->getCardName();
        $object_ws->fechaExpiracion = $paymentMethod->getCardExpYear() .'/'. $paymentMethod->getCardExpMonth();
        $object_ws->validarModuloAntiFraude = true;
        $object_ws->reportarPaginaConfirmacion = false;
        //Antifraude
        $object_ws->ciudadCorrespondencia = $extraData['correspondence_city'];
        $object_ws->cookie = $paymentMethod->getCookie();
        $object_ws->direccionCorrespondencia = $extraData['correspondence_address'];
        $object_ws->ipComprador = $paymentMethod->getClientIp();
        $object_ws->paisCorrespondencia =  'CO';
        $object_ws->userAgent = $paymentMethod->getUserAgent();

        $autWS = $this->pagosonlineComm->solicitarAutorizacion($object_ws);
        $this->logger->addInfo($paymentMethod->getPaymentName(), get_object_vars($object_ws));

        $paymentMethod->setPagosonlineTransactionId($autWS->transaccionId);
        $paymentMethod->setPagosonlineReference($autWS->referencia);
        $this->processTransaction($autWS, $paymentMethod);

        return $this;
    }

    /**
     * Given a paymillTransaction response, as an array, prform desired operations
     *
     * @param array             $autWS
     * @param PagosonlineMethod $paymentMethod Payment method
     *
     * @return PagosonlineManager Self object
     *
     * @throws PaymentException
     */
    private function processTransaction($autWS, PagosonlineMethod $paymentMethod)
    {

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransaction', get_object_vars($autWS));
        /**
         * if pagosonline return code 15 o 9994 the order status is pending
         */
        if (in_array($autWS->codigoRespuesta, array('15','9994'))) {

            //payment is still pending nothing to do

        } elseif ($autWS->codigoRespuesta == 1) {

            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        } else {

            $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
            throw new PaymentException();
        }

        /**
         * Log the response of gateway
         */

        return $this;
    }
}
