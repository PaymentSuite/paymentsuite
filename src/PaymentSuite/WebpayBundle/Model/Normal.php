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
 * Normal Transaction Model
 */
class Normal extends Transaction
{
    /**
     * @var string
     *
     * accion (TBK_ACCION)
     */
    protected $accion;

    /**
     * Sets Accion
     *
     * @param string $accion Accion
     *
     * @return Normal Self object
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get Accion
     *
     * @return string Accion
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * @var string
     *
     * ordenCompra (TBK_ORDEN_COMPRA)
     */
    protected $ordenCompra;

    /**
     * Sets OrdenCompra
     *
     * @param string $ordenCompra OrdenCompra
     *
     * @return Normal Self object
     */
    public function setOrdenCompra($ordenCompra)
    {
        $this->ordenCompra = $ordenCompra;

        return $this;
    }

    /**
     * Get OrdenCompra
     *
     * @return string OrdenCompra
     */
    public function getOrdenCompra()
    {
        return $this->ordenCompra;
    }

    /**
     * @var integer
     *
     * codigoComercio (TBK_CODIGO_COMERCIO)
     */
    protected $codigoComercio;

    /**
     * Sets CodigoComercio
     *
     * @param int $codigoComercio CodigoComercio
     *
     * @return Normal Self object
     */
    public function setCodigoComercio($codigoComercio)
    {
        $this->codigoComercio = $codigoComercio;

        return $this;
    }

    /**
     * Get CodigoComercio
     *
     * @return int CodigoComercio
     */
    public function getCodigoComercio()
    {
        return $this->codigoComercio;
    }

    /**
     * @var string
     *
     * codigoComercioEnc (TBK_CODIGO_COMERCIO_ENC)
     */
    protected $codigoComercioEnc;

    /**
     * Sets CodigoComercioEnc
     *
     * @param string $codigoComercioEnc CodigoComercioEnc
     *
     * @return Normal Self object
     */
    public function setCodigoComercioEnc($codigoComercioEnc)
    {
        $this->codigoComercioEnc = $codigoComercioEnc;

        return $this;
    }

    /**
     * Get CodigoComercioEnc
     *
     * @return string CodigoComercioEnc
     */
    public function getCodigoComercioEnc()
    {
        return $this->codigoComercioEnc;
    }

    /**
     * @var integer
     *
     * respuesta (TBK_RESPUESTA)
     */
    protected $respuesta;

    /**
     * Sets Respuesta
     *
     * @param int $respuesta Respuesta
     *
     * @return Normal Self object
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get Respuesta
     *
     * @return int Respuesta
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * @var integer
     *
     * monto (TBK_MONTO)
     */
    protected $monto;

    /**
     * Sets Monto
     *
     * @param int $monto Monto
     *
     * @return Normal Self object
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get Monto
     *
     * @return int Monto
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @var string
     *
     * codigoAutorizacion (TBK_CODIGO_AUTORIZACION)
     */
    protected $codigoAutorizacion;

    /**
     * Sets CodigoAutorizacion
     *
     * @param string $codigoAutorizacion CodigoAutorizacion
     *
     * @return Normal Self object
     */
    public function setCodigoAutorizacion($codigoAutorizacion)
    {
        $this->codigoAutorizacion = $codigoAutorizacion;

        return $this;
    }

    /**
     * Get CodigoAutorizacion
     *
     * @return string CodigoAutorizacion
     */
    public function getCodigoAutorizacion()
    {
        return $this->codigoAutorizacion;
    }

    /**
     * @var integer
     *
     * finalNumeroTarjeta (TBK_FINAL_NUMERO_TARJETA)
     */
    protected $finalNumeroTarjeta;

    /**
     * Sets FinalNumeroTarjeta
     *
     * @param int $finalNumeroTarjeta FinalNumeroTarjeta
     *
     * @return Normal Self object
     */
    public function setFinalNumeroTarjeta($finalNumeroTarjeta)
    {
        $this->finalNumeroTarjeta = $finalNumeroTarjeta;

        return $this;
    }

    /**
     * Get FinalNumeroTarjeta
     *
     * @return int FinalNumeroTarjeta
     */
    public function getFinalNumeroTarjeta()
    {
        return $this->finalNumeroTarjeta;
    }

    /**
     * @var integer
     *
     * fechaContable (TBK_FECHA_CONTABLE)
     * Format: mmdd
     */
    protected $fechaContable;

    /**
     * Sets FechaContable
     *
     * @param int $fechaContable FechaContable
     *
     * @return Normal Self object
     */
    public function setFechaContable($fechaContable)
    {
        $this->fechaContable = $fechaContable;

        return $this;
    }

    /**
     * Get FechaContable
     *
     * @return int FechaContable
     */
    public function getFechaContable()
    {
        return $this->fechaContable;
    }

    /**
     * @var integer
     *
     * fechaTransaccion (TBK_FECHA_TRANSACCIÓN)
     * Format: mmdd
     */
    protected $fechaTransaccion;

    /**
     * Sets FechaTransaccion
     *
     * @param int $fechaTransaccion FechaTransaccion
     *
     * @return Normal Self object
     */
    public function setFechaTransaccion($fechaTransaccion)
    {
        $this->fechaTransaccion = $fechaTransaccion;

        return $this;
    }

    /**
     * Get FechaTransaccion
     *
     * @return int FechaTransaccion
     */
    public function getFechaTransaccion()
    {
        return $this->fechaTransaccion;
    }

    /**
     * @var integer
     *
     * fechaExpiracion (TBK_FECHA_EXPIRACION)
     * Format: aamm
     */
    protected $fechaExpiracion;

    /**
     * Sets FechaExpiracion
     *
     * @param int $fechaExpiracion FechaExpiracion
     *
     * @return Normal Self object
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;

        return $this;
    }

    /**
     * Get FechaExpiracion
     *
     * @return int FechaExpiracion
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * @var integer
     *
     * horaTransaccion (TBK_HORA_TRANSACCIÓN)
     * Format: hhmmss
     */
    protected $horaTransaccion;

    /**
     * Sets HoraTransaccion
     *
     * @param int $horaTransaccion HoraTransaccion
     *
     * @return Normal Self object
     */
    public function setHoraTransaccion($horaTransaccion)
    {
        $this->horaTransaccion = $horaTransaccion;

        return $this;
    }

    /**
     * Get HoraTransaccion
     *
     * @return int HoraTransaccion
     */
    public function getHoraTransaccion()
    {
        return $this->horaTransaccion;
    }

    /**
     * @var string
     *
     * idSesion (TBK_ID_SESION)
     */
    protected $idSesion;

    /**
     * Sets IdSesion
     *
     * @param string $idSesion IdSesion
     *
     * @return Normal Self object
     */
    public function setIdSesion($idSesion)
    {
        $this->idSesion = $idSesion;

        return $this;
    }

    /**
     * Get IdSesion
     *
     * @return string IdSesion
     */
    public function getIdSesion()
    {
        return $this->idSesion;
    }

    /**
     * @var integer
     *
     * idTransaccion (TBK_ID_TRANSACCIÓN)
     */
    protected $idTransaccion;

    /**
     * Sets IdTransaccion
     *
     * @param int $idTransaccion IdTransaccion
     *
     * @return Normal Self object
     */
    public function setIdTransaccion($idTransaccion)
    {
        $this->idTransaccion = $idTransaccion;

        return $this;
    }

    /**
     * Get IdTransaccion
     *
     * @return int IdTransaccion
     */
    public function getIdTransaccion()
    {
        return $this->idTransaccion;
    }

    /**
     * @var string
     *
     * tipoPago (TBK_TIPO_PAGO)
     */
    protected $tipoPago;

    /**
     * Sets TipoPago
     *
     * @param string $tipoPago TipoPago
     *
     * @return Normal Self object
     */
    public function setTipoPago($tipoPago)
    {
        $this->tipoPago = $tipoPago;

        return $this;
    }

    /**
     * Get TipoPago
     *
     * @return string TipoPago
     */
    public function getTipoPago()
    {
        return $this->tipoPago;
    }

    /**
     * @var integer
     *
     * numeroCuotas (TBK_NUMERO_CUOTAS)
     */
    protected $numeroCuotas;

    /**
     * Sets NumeroCuotas
     *
     * @param int $numeroCuotas NumeroCuotas
     *
     * @return Normal Self object
     */
    public function setNumeroCuotas($numeroCuotas)
    {
        $this->numeroCuotas = $numeroCuotas;

        return $this;
    }

    /**
     * Get NumeroCuotas
     *
     * @return int NumeroCuotas
     */
    public function getNumeroCuotas()
    {
        return $this->numeroCuotas;
    }

    /**
     * @var string
     *
     * vci (TBK_VCI)
     */
    protected $vci;

    /**
     * Sets Vci
     *
     * @param string $vci Vci
     *
     * @return Normal Self object
     */
    public function setVci($vci)
    {
        $this->vci = $vci;

        return $this;
    }

    /**
     * Get Vci
     *
     * @return string Vci
     */
    public function getVci()
    {
        return $this->vci;
    }

    /**
     * @var string
     *
     * mac (TBK_MAC)
     */
    protected $mac;

    /**
     * Sets Mac
     *
     * @param string $mac Mac
     *
     * @return Normal Self object
     */
    public function setMac($mac)
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * Get Mac
     *
     * @return string Mac
     */
    public function getMac()
    {
        return $this->mac;
    }
}