<?php

namespace PaymentSuite\RedsysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RedsysBundle:Default:index.html.twig', array('name' => $name));
    }
}
