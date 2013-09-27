<?php

/**
 * PaymentCoreBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymentCoreBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymentCoreBundle\Services\Interfaces;


/**
 * Extra data validator interface
 */
interface PaymentExtraDataValidatorInterface
{

    /**
     * Return fields to parse from PaymentBridge object
     * 
     * @return array fields
     */
    public function getFields();
}