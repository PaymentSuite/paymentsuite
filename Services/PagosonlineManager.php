<?php


namespace Scastells\PagosonlineBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Scastells\PagosonlineBundle\PagosonlineMethod;
use Scastells\PagosonlineBundle\Lib\WSSESoap;
use Scastells\PagosonlineBundle\Lib\WSSESoapClient;

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
     * @var string
     *
     * user pagosonline
     */
    private $userId;


    /**
     * @var string
     *
     * wsdl pagosonline
     */
    private $wsdl;


    /**
     * @var string
     *
     * password pagosonline
     */
    private $password;


    /**
     * @var account
     *
     * account pagosonlie
     */
    private $accountId;


    /**
     * Construct method for pagosonline manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param $userId
     * @param $wsdl
     * @param $password
     * @param $accountId
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge, $userId, $password, $accountId, $wsdl)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->userId = $userId;
        $this->password = $password;
        $this->accountId = $accountId;
        $this->wsdl = $wsdl;
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

        $client = new WSSESoapClient($this->wsdl, $this->userId, $this->password);

        $autWS = $client->solicitarAutorizacion($object_ws);

        $paymentMethod->setPagosonlineTransactionId($autWS->transaccionId);
        $paymentMethod->setPagosonlineReference($autWS->referencia);
        $this->processTransaction($autWS, $paymentMethod);

        return $this;
    }


    /**
     * Given a paymillTransaction response, as an array, prform desired operations
     *
     * @param array         $autWS
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
        /**
         * if pagosonline return code 15 o 9994 the order status is pendeing
         */
        if (in_array($autWS->codigoRespuesta, array('15','9994'))) {

            /**
             * Payment paid failed
             *
             * Paid process has ended failed
             */
            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);


        } elseif ($autWS->codigoRespuesta == 1) {

            $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        } else {

            throw new PaymentException;
        }


        /**
         * Adding to PaymentMethod transaction information
         *
         * This information is only available in PaymentOrderSuccess event
         */
        //set transaction id

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */

        return $this;
    }
}