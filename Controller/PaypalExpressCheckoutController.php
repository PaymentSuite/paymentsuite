<?php

/**
 * PaypalExpressCheckout for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalExpressCheckout
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\PaypalExpressCheckout\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PaymentSuite\PaypalExpressCheckout\PaypalExpressCheckoutMethod;

/**
 * PaypalExpressCheckoutController
 */
class PaypalExpressCheckoutController extends Controller
{

    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        
    }
}