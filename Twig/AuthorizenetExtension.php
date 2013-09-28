<?php

/**
 * AuthorizenetBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package AuthorizenetBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\AuthorizenetBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Extension,
    Twig_SimpleFunction;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use dpcat237\AuthorizenetBundle\Router\AuthorizenetRoutesLoader;

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
     * @var string
     *
     * Public key
     */
    private $publicKey;


    /**
     * @var PaymentBridgeInterface
     *
     * Currency wrapper
     */
    private $paymentBridgeInterface;


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