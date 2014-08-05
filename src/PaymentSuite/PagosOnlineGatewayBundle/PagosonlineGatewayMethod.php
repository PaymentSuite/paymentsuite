<?php

/**
 * PagosOnline for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PagosOnlineGatewayBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PagosOnlineMethod class
 */
class PagosonlineGatewayMethod implements PaymentMethodInterface
{
    /**
     * Get pagosOnline method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'PagosonlineGateway';
    }

    /**
     * @var float
     *
     * pagosOnline amount
     */
    private $amount;

    /**
     * @var string
     *
     * pagosonline gateway transactionid
     */
    private $pagosonlineGatewayTransactionId;

    /**
     * @var string
     *
     * pagosonline gateway reference
     */

    private $pagosonlineGatewayReference;

    /**
     * @var string reference
     *
     * order reference add #
     */

    private $reference;

    /**
     * @var status
     * pagosonline gateway status
     */
    private $status;

    /**
     * @param $status integer
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
     return $this->status;
    }

    /**
     * @param string $pagosonlineGatewayTransactionId
     *
     * @return $this
     */
    public function setPagosonlineGatewayTransactionId($pagosonlineGatewayTransactionId)
    {
        $this->pagosonlineGatewayTransactionId = $pagosonlineGatewayTransactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPagosonlineGatewayTransactionId()
    {
        return $this->pagosonlineGatewayTransactionId;
    }

    /**
     * @param string $pagosonlineGatewayReference
     *
     * @return $this
     */
    public function setPagosonlineGatewayReference($pagosonlineGatewayReference)
    {
        $this->pagosonlineGatewayReference = $pagosonlineGatewayReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getPagosonlineGatewayReference()
    {
        return $this->pagosonlineGatewayReference;
    }

    /**
     * set amount
     *
     * @param float $amount Amount
     *
     * @return PagosonlineGatewayMethod self Object
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

}
