<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
 */

namespace PaymentSuite\WebpayBundle\Model;

/**
 * Abstract Model class for transaction models
 */
abstract class Transaction
{
    /**
     * @var string
     *
     * tipoTransaccion (TBK_TIPO_TRANSACCION)
     */
    protected $tipoTransaccion;

    /**
     * Sets TipoTransaccion
     *
     * @param string $tipoTransaccion TipoTransaccion
     *
     * @return Transaction Self object
     */
    public function setTipoTransaccion($tipoTransaccion)
    {
        $this->tipoTransaccion = $tipoTransaccion;

        return $this;
    }

    /**
     * Get TipoTransaccion
     *
     * @return string TipoTransaccion
     */
    public function getTipoTransaccion()
    {
        return $this->tipoTransaccion;
    }
}
