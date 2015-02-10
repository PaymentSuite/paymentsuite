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
