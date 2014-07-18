<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle;

use PaymentSuite\PayuBundle\PayuMethod;

/**
 * VisanetMethod
 */
class VisanetMethod extends PayuMethod
{
    /**
     * Method name
     */
    const METHOD_NAME = 'Visanet';
}