<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\AuthorizenetBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\AuthorizenetBundle\Router\AuthorizenetRoutesLoader;

/**
 * Text utilities extension
 *
 */
class AuthorizenetExtension extends Twig_Extension
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

    /**
     * @var Twig_Environment
     *
     * Twig environment
     */
    private $environment;

    /**
     * Construct method
     *
     * @param FormFactory $formFactory
     *
     * @return AuthorizenetExtension Form factory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Init runtime
     *
     * @param \Twig_Environment $environment Twig environment
     *
     * @return AuthorizenetExtension self object
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('authorizenet_render', array($this, 'renderPaymentView')),
        );
    }

    /**
     * Render authorizenet form view
     *
     * @return string view html
     */
    public function renderPaymentView()
    {
        $formType = $this->formFactory->create('authorizenet_view');

        return $this->environment->display('AuthorizenetBundle:Authorizenet:view.html.twig', array(
            'authorizenet_form'  =>  $formType->createView(),
            'authorizenet_execute_route' =>  AuthorizenetRoutesLoader::ROUTE_NAME,
        ));
    }

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_authorizenet_extension';
    }
}
