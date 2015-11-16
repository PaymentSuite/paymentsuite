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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

/**
 * Type for a shop edit profile form
 */
class PaypalExpressCheckoutType extends AbstractType
{
    /**
     * @var PaymentBridgeInterface
     *
     * Payment Wrapper
     */
    private $paymentBridge;

    /**
     * @var Router
     *
     * Router instance
     */
    private $router;

    /**
     * Formtype construct method
     *
     * @param Router                 $router        Router instance
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     */
    public function __construct(
        Router $router,
        PaymentBridgeInterface $paymentBridge
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->router = $router;
    }

    /**
     * Buildform function
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this
                ->router
                ->generate(
                    'paymentsuite_paypalexpresscheckout_execute',
                    [],
                    true
                )
            )
            ->setMethod('POST')

            /**
             * Some hidden fields
             */
            ->add('amount', 'hidden', [
                'data'  =>  $this->paymentBridge->getAmount(),
            ])
            ->add('currency', 'hidden', [
                'data'  =>  $this->paymentBridge->getCurrency(),
            ])
            ->add('paypal_express_params', 'hidden', [
                'data'  => $options['paypal_express_params'],
            ])
            ->add('submit', 'submit');
    }

    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'paypal_express_checkout_view';
    }
}
