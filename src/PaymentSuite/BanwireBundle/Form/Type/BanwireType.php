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

namespace PaymentSuite\BanwireBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

class BanwireType extends AbstractType
{
    /**
     * @var PaymentBridgeInterface
     *
     * Card Wrapper
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
             * Credit card type
             */
            ->add('card_type', 'choice', array(
                'required' => true,
                'choices' => array(
                    'visa' => 'Visa', 'mastercard' => 'MasterCard', 'amex' => 'American Express'
                ),
            ))

            /**
             * Card Owner
             */
            ->add('card_name', 'text', array(
                'required' => true,
            ))

            /**
             * Credit card number
             */
            ->add('card_num', 'text', array(
                'required' => true,
                'max_length' => 16,
            ))

            /**
             * Credit card expiration
             */
            ->add('card_exp_month', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1,12), range(1, 12)),
            ))
            ->add('card_exp_year', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(2013, 2025), range(2013, 2025)),
            ))

            /**
             * Credit card security
             */
            ->add('card_ccv2', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            /**
             * Some hidden fields
             */
            ->add('amount', 'hidden', array(
                'data'  =>  $this->paymentBridge->getAmount()
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
        return 'banwire_view';
    }
}
