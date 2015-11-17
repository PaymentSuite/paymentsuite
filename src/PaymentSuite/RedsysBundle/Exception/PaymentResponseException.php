<?php
/*
 * This file is part of the Mascoteros package.
 *
 * Copyright (c) 2015-2016 Mascoteros.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace PaymentSuite\RedsysBundle\Exception;


use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * Class PaymentResponseException
 * @package PaymentSuite\RedsysBundle\Exception
 */
class PaymentResponseException extends PaymentException
{
    /**
     * Redsys response error codes and msg
     *
     * @var array
     */
    private $responseErrors = [
        '0101' => 'Tarjeta caducada',
        '0102' => 'Tarjeta en excepción transitoria o bajo sospecha de fraude',
        '0106' => 'Intentos de PIN excedidos',
        '0125' => 'Tarjeta no efectiva',
        '0129' => 'Código de seguridad (CVV2/CVC2) incorrecto',
        '0180' => 'Tarjeta ajena al servicio',
        '0184' => 'Error en la autenticación del titular',
        '0190' => 'Denegación sin especificar Motivo',
        '0191' => 'Fecha de caducidad errónea',
        '0202' => 'Tarjeta en excepción transitoria o bajo sospecha de fraude con retirada de tarjeta',
        '0904' => 'Comercio no registrado en FUC',
        '0909' => 'Error de sistema',
        '0912' => 'Emisor no disponible',
        '0913' => 'Pedido repetido',
        '0944' => 'Sesión Incorrecta',
        '0950' => 'Operación de devolución no permitida',
        '9064' => 'Número de posiciones de la tarjeta incorrecto',
        '9078' => 'No existe método de pago válido para esa tarjeta',
        '9093' => 'Tarjeta no existente',
        '9094' => 'Rechazo servidores internacionales',
        '9104' => 'Comercio con “titular seguro” y titular sin clave de compra segura',
        '9218' => 'El comercio no permite operaciones seguras por entrada /operaciones',
        '9253' => 'Tarjeta no cumple el check-digit',
        '9256' => 'El comercio no puede realizar preautorizaciones',
        '9257' => 'Esta tarjeta no permite operativa de preautorizaciones',
        '9261' => 'Operación detenida por superar el control de restricciones en la entrada al SIS',
        '9912' => 'Emisor no disponible',
        '9913' => 'Error en la confirmación que el comercio envía al TPV Virtual',
        '9914' => 'Confirmación “KO” del comercio (solo aplicable en la opción de sincronización SOAP)',
        '9915' => 'A petición del usuario se ha cancelado el pago',
        '9928' => 'Anulación de autorización en diferido realizada por el SIS (proceso batch)',
        '9929' => 'Anulación de autorización en diferido realizada por el comercio',
        '9997' => 'Se está procesando otra transacción en SIS con la misma tarjeta',
        '9998' => 'Operación en proceso de solicitud de datos de la tarjeta.El sistema queda a la espera de que el titular inserte la tarjeta, la operación no se procesa',
        '9999' => 'Operación que ha sido redirigida al emisor a autenticar'
    ];

    /**
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = '', $code = 0)
    {
        if (!isset($this->responseErrors[$code])) {
            $message = 'Error desconocido en la respuesta procedente de Redsys: Ds_Response '.$code;
        } else {
            $message = $this->responseErrors[$code];
        }

        parent::__construct($message);
    }
}