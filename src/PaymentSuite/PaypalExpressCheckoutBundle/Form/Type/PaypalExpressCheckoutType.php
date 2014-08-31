<?php

/**
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
     * @var string
     *
     * Execution route name
     */
    private $controllerRouteName;

    /**
     * Formtype construct method
     *
     * @param Router                 $router              Router instance
     * @param PaymentBridgeInterface $paymentBridge       Payment bridge
     * @param string                 $controllerRouteName Controller route name
     */
    public function __construct(Router $router, PaymentBridgeInterface $paymentBridge, $controllerRouteName)
    {
        $this->paymentBridge = $paymentBridge;
        $this->router = $router;
        $this->controllerRouteName = $controllerRouteName;
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
            ->setAction($this->router->generate($this->controllerRouteName, array(), true))
            ->setMethod('POST')

            /**
             * Some hidden fields
             */
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->paymentBridge->getAmount(), 2) * 100
            ))
            ->add('currency', 'hidden', array(
                'data'  =>  $this->paymentBridge->getCurrency()
            ))
            ->add('paypal_express_params', 'hidden', array(
                'data'  => $options['paypal_express_params']
            ))
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
