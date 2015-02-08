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

namespace PaymentSuite\PaymillBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

/**
 * Type for a shop edit profile form
 */
class PaymillType extends AbstractType
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
     * @var string
     *
     * Label for the submit button
     */
    private $submitLabel;

    /**
     * @var string
     *
     * CSS Class for the submit button
     */
    private $submitCssClass;

    /**
     * Formtype construct method
     *
     * @param Router                 $router              Router instance
     * @param PaymentBridgeInterface $paymentBridge       Payment bridge
     * @param string                 $controllerRouteName Controller route name
     */
    public function __construct(Router $router,
                                PaymentBridgeInterface $paymentBridge,
                                $controllerRouteName,
                                $submitLabel,
                                $submitCssClass)
    {
        $this->paymentBridge = $paymentBridge;
        $this->router = $router;
        $this->controllerRouteName = $controllerRouteName;
        $this->submitLabel = $submitLabel;
        $this->submitCssClass = $submitCssClass;
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
             * Credit card number
             */
            ->add('credit_card_1', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_card_2', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_card_3', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_card_4', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            /**
             * Card Owner
             */
            ->add('credit_card_owner', 'text', array(
                'required' => true,
            ))

            /**
             * Credit card expiration
             */
            ->add('credit_card_expiration_month', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1, 12), range(1, 12)),
            ))
            ->add('credit_card_expiration_year', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(2013, 2025), range(2013, 2025)),
            ))

            /**
             * Credit card security
             */
            ->add('credit_card_security', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            /**
             * Some hidden fields
             */
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->paymentBridge->getAmount(), 2) * 100
            ))
            ->add('api_token', 'hidden', array(
                'data'  =>  ''
            ))
            ->add('submit', 'submit', array(
                'label' => $this->submitLabel,
                'attr' => array('class' => $this->submitCssClass)
            ));
    }

    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'paymill_view';
    }
}
