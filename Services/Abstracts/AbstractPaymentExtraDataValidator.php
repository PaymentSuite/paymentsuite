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

namespace Mmoreram\PaymentCoreBundle\Services\Abstracts;

use Mmoreram\PaymentCoreBundle\Exception\PaymentExtraDataFieldNotDefinedException;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentExtraDataValidatorInterface;
use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Extra data validator abstract class
 */
abstract class AbstractPaymentExtraDataValidator implements PaymentExtraDataValidatorInterface
{

    /**
     * @var PaymentBridge
     *
     * Payment bridge
     */
    private $paymentBridge;


    /**
     * Construct method
     *
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }


    /**
     * validate method
     * 
     * @return AbstractPaymentExtraDataValidator self Object
     *
     * @throws PaymentExtraDataFieldNotDefinedException Extra data field not defined
     */
    public function validate()
    {
        $expectedFields = $this->getFields();
        $existentFields = array_keys($this->paymentBridge->getExtraData());
        $fieldsNotDefined = array_diff($expectedFields, $existentFields);

        if (!empty($fieldsNotDefined)) {

            /**
             * Some extra fields are not defined in extraParams
             */
            throw new PaymentExtraDataFieldNotDefinedException('Some extra data fields are not defined in PaymentBridge implementation. Fields (' . implode(', ', $expectedFields) . ') are expected, fields (' . implode(', ', $existentFields) . ') found. Fields (' . implode(', ', $fieldsNotDefined) . ') are missing');
        }

        return $this;
    }
}