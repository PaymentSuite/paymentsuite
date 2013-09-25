<?php

namespace Scastells\PagosOnline\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\PagosOnlineBundle\PagosOnlineMethod;

/**
 * PagosOnlineController
 */
class PagosOnlineController extends Controller
{
    /**
     * @param Request $request
     * @return bool
     */
    public function executeAction(Request $request)
    {
        return false;
    }
}
