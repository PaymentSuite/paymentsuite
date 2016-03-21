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

namespace PaymentSuite\RedsysApiBundle\Twig;

use PaymentSuite\RedsysApiBundle\Router\RedsysApiRoutesLoader;
use Symfony\Component\Form\FormFactory;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class RedsysApiExtension extends Twig_Extension
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

    /**
     * @var \Twig_Environment
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
     * @var string
     *
     * View template name in Bundle notation
     */
    protected $viewTemplate;

    /**
     * @var string
     *
     * Scripts template in Bundle notation
     */
    protected $scriptsTemplate;

    /**
     * Construct method
     *
     * @param FormFactory            $formFactory            Form factory
     * @param PaymentBridgeInterface $paymentBridgeInterface Payment Bridge Interface
     * @param string                 $viewTemplate           Twig template name for displaying the form
     * @param string                 $scriptsTemplate        Twig template name for scripts/js
     */
    public function __construct(
        FormFactory $formFactory,
        PaymentBridgeInterface $paymentBridgeInterface,
        $viewTemplate,
        $scriptsTemplate
    ) {
        $this->formFactory = $formFactory;
        $this->paymentBridgeInterface = $paymentBridgeInterface;
        $this->viewTemplate = $viewTemplate;
        $this->scriptsTemplate = $scriptsTemplate;
    }

    /**
     * Init runtime
     *
     * @param \Twig_Environment $environment Twig environment
     *
     * @return $this self object
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
            new Twig_SimpleFunction('redsys_api_render', array($this, 'renderPaymentView')),
        );
    }

    /**
     * Render redsys api form view
     *
     * @param string $viewTemplate An optional template to render.
     *
     * @return string view html
     */
    public function renderPaymentView($viewTemplate = null)
    {
        $formType = $this->formFactory->create('redsys_api_type');

        $this->environment->display($viewTemplate ?: $this->viewTemplate, array(
            'redsys_api_form'  =>  $formType->createView(),
            'redsys_api_execute_route' =>  RedsysApiRoutesLoader::ROUTE_NAME,
        ));
    }

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_redsys_api_extension';
    }
}
