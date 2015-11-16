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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Text utilities extension
 *
 */
class PaypalExpressCheckoutExtension extends Twig_Extension
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    private $formFactory;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment Bridge
     */
    private $paymentBridgeInterface;

    /**
     * Construct method
     *
     * @param FormFactory            $formFactory            Form factory
     * @param PaymentBridgeInterface $paymentBridgeInterface Payment Bridge
     */
    public function __construct(
        FormFactory $formFactory,
        PaymentBridgeInterface $paymentBridgeInterface
    ) {
        $this->formFactory = $formFactory;
        $this->paymentBridgeInterface = $paymentBridgeInterface;
    }

    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('paypal_express_checkout_render', function (Twig_Environment $environment) {

                $formType = $this
                    ->formFactory
                    ->create('paypal_express_checkout_view');

                $environment->display('PaypalExpressCheckoutBundle:PaypalExpressCheckout:view.html.twig', [
                        'paypal_express_checkout_form' => $formType->createView(),
                    ]);
            }, [
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'paymentsuite_payment_paypal_express_checkout';
    }
}
