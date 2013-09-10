<?php

/**
 * PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\PaymillBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\CartWrapperInterface;

/**
 * Type for a shop edit profile form
 */
class PaymillType extends AbstractType
{

    /**
     * @var CartWrapperInterface
     *
     * Cart Wrapper
     */
    private $cartWrapper;


    /**
     * Formtype construct method
     *
     * @param CartWrapperInterface $cartWrapper Cart wrapper
     */
    public function __construct(CartWrapperInterface $cartWrapper)
    {
        $this->cartWrapper = $cartWrapper;
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
            ->add('credit_cart_1', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_cart_2', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_cart_3', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_cart_4', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_cart_owner', 'text', array(
                'required' => true,
            ))
            ->add('credit_cart_expiration_month', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1, 12), range(1, 12)),
            ))
            ->add('credit_cart_expiration_year', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(2013, 2025), range(2013, 2025)),
            ))
            ->add('credit_cart_security', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->cartWrapper->getAmount(), 2) * 100
            ))
            ->add('api_token', 'hidden', array(
                'data'  =>  ''
            ))
            ->add('payment_processer', 'hidden', array(
                'data'  =>  'paymill_processer'
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
        return 'paymill_view';
    }
}