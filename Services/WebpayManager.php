<?php

namespace PaymentSuite\WebpayBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\WebpayBundle\WebpayMethod;
use PaymentSuite\WebpayBundle\Exception\WebpayMacCheckException;

/**
 * Webpay manager
 */
class WebpayManager
{
    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $eventDispatcher;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;

    /**
     * @var string
     */
    protected $kccPath;

    /**
     * Construct method for Webpay manager
     *
     * @param PaymentEventDispatcher $eventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge   Payment Bridge
     * @param string                 $kccPath         Path to kcc
     */
    public function __construct(PaymentEventDispatcher $eventDispatcher, PaymentBridgeInterface $paymentBridge, $kccPath)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->kccPath = $kccPath;
    }

    /**
     * Generate log transaction file for KCC
     *
     * @return string TBK Session Id
     */
    public function processPayment()
    {
        $tbkOrdenCompra = $this->paymentBridge->getOrderId();
        $tbkTotal = floor($this->paymentBridge->getAmount() * 100);
        $tbkIdSesion = $tbkOrdenCompra . date('Ymdhis');

        // Generate session log file for KCC
        $file = fopen($this->kccPath . '/log/datos' . $tbkIdSesion . '.log', 'w');
        $line = $tbkTotal . ';' . $tbkOrdenCompra;
        fwrite($file, $line);
        fclose($file);

        return $tbkIdSesion;
    }

    /**
     * Confirm webpay payment
     *
     * @param WebpayMethod $paymentMethod Payment Method
     * @param array        $postData      Post parameters
     *
     * @throws \PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     * @throws \PaymentSuite\WebpayBundle\Exception\WebpayMacCheckException
     * @throws \PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException
     * @throws \PaymentSuite\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException
     *
     * @return WebpayManager Self object
     */
    public function confirmPayment(WebpayMethod $paymentMethod, array $postData)
    {
        $paymentBridge = $this->paymentBridge;

        $tbkRespuesta = $postData['TBK_RESPUESTA'];
        $tbkOrdenCompra = $postData['TBK_ORDEN_COMPRA'];
        $tbkMonto = $postData['TBK_MONTO'];
        $tbkIdSesion = $postData['TBK_ID_SESION'];

        $paymentMethod->setTransactionId($tbkIdSesion);
        $paymentMethod->setAmount($tbkMonto);

        // Check TBK_ORDEN_COMPRA
        $this->eventDispatcher->notifyPaymentOrderLoad($paymentBridge, $paymentMethod);
        if (!$paymentBridge->getOrder() || $paymentBridge->getOrderId() != $tbkOrdenCompra) {
            throw new PaymentOrderNotFoundException;
        }

        // Check TBK_RESPUESTA
        if ($tbkRespuesta != '0') {
            $this->eventDispatcher->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
            throw new PaymentException;
        }

        // Check MAC
        $fileMacName = $this->kccPath . '/log/MAC01Normal' . $paymentMethod->getTransactionId() . '.txt';
        $fileMac = fopen($fileMacName, 'w');
        foreach ($postData as $key => $val) {
            fwrite($fileMac, "$key=$val&");
        }
        fclose($fileMac);
        $cmd = $this->kccPath . '/tbk_check_mac.cgi ' . $fileMacName . ' 2>&1';
        exec($cmd, $result, $retint);
        if ($retint != 0 || $result[0] != 'CORRECTO') {
            $this->eventDispatcher->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
            throw new WebpayMacCheckException;
        }

        // Check MONTO
        $fileMontoName = $this->kccPath . '/log/datos' . $paymentMethod->getTransactionId() . '.log';
        if (!$fileMonto = fopen($fileMontoName, 'r')) {
            $this->eventDispatcher->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
            throw new PaymentAmountsNotMatchException;
        }
        $line = trim(fgets($fileMonto));
        fclose($fileMonto);
        $details = explode(";", $line);
        if (count($details) != 2) {
            $this->eventDispatcher->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
            throw new PaymentAmountsNotMatchException;
        }
        if ($tbkMonto != $details[0] || $tbkOrdenCompra != $details[1]) {
            $this->eventDispatcher->notifyPaymentOrderFail($paymentBridge, $paymentMethod);
            throw new PaymentAmountsNotMatchException;
        }

        // Check DUPLICIDAD
        /*if ($order->current_state != Configuration::get('WEBPAY_WAIT_STATUS')) {
            $order->setCurrentState(Configuration::get('PS_OS_CANCELED'));
            die('RECHAZADO');
        }*/

        $this->eventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);

        return $this;
    }
}
