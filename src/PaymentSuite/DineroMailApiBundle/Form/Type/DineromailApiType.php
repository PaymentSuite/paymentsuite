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

namespace PaymentSuite\DineroMailApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

class DineromailApiType extends AbstractType
{

    /*
     * @var PaymentBridgeInterface
     *
     * Cart Wrapper
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
     * FormType construct method
     *
     * @param \Symfony\Component\Routing\Router $router
     * @param PaymentBridgeInterface            $paymentBridge       Payment bridge
     * @param string                            $controllerRouteName
     * @internal param \Symfony\Component\Routing\Router $router
     */
    public function __construct(Router $router, PaymentBridgeInterface $paymentBridge, $controllerRouteName)
    {
        $this->paymentBridge = $paymentBridge;
        $this->router = $router;
        $this->controllerRouteName = $controllerRouteName;
    }

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
                    'VISA' => 'Visa', 'MASTER' => 'MasterCard', 'AMEX' => 'American Express', 'ITALCRED' => 'ItalCred',
                    'TSHOPPING' => 'Tarjeta Shopping', 'TNARANJA' => 'Tarjeta Naranja', 'CABAL' => 'Cabal', 'ARGENCARD' => 'ArgenCard'
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
            ->add('card_num_1', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            ->add('card_num_2', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            ->add('card_num_3', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            ->add('card_num_4', 'text', array(
                'required' => true,
                'max_length' => 4,
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
             * Number of quotes
             */
            ->add('card_quotas', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1, 36), range(1, 36)),
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
        return 'dineromail_api_view';
    }
}
