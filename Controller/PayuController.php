<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * PayuController
 */
class PayuController extends Controller
{
    /**
     * Payment notification
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("POST")
     */
    public function notifyAction(Request $request)
    {
        $this->get('logger')->addInfo('PayuNotify', $request->request->all());

        return new Response();
    }
}