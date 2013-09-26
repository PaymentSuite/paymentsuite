<?php

namespace Scastells\PagosonlineBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Scastells\PagosonlineBundle\PagosonlineMethod;

/**
 * PagosOnlineController
 */
class PagosonlineController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Method("POST")
     */
    public function executeAction(Request $request)
    {
        $form = $this->get('form.factory')->create('pagosonline_view');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('pagosonline.manager')
                ->processPayment();
        }


        return true;
    }
}
