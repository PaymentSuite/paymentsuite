<?php

namespace Scastells\PagosonlineBundle\Controller;


use Scastells\PagosonlineBundle\Lib\WSSESoap;
use Scastells\PagosonlineBundle\Lib\WSSESoapClient;
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
    protected $usuario_id_pr;
    protected $cuenta_id_pr;
    protected $usuario_id_ws;
    protected $cuenta_id_ws;
    protected $wsdl_url;
    protected $pwd_pr;
    protected $pwd_ws;

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

            $wsdl = $this->container->getParameter('wsdl_url');
            $user = $this->container->getParameter('pagosonline.user_id');
            $pass = $this->container->getParameter('pagosonline.password');

            $client = new WSSESoapClient($wsdl, $user, $pass);

            var_dump($client->getVersion());














            die("hola");

            $data = $form->getData();
            $this->get('pagosonline.manager')
                ->processPayment();
        }
        var_dump($request->query->get('card_num'));
         die('pepe');

        return true;
    }
}
