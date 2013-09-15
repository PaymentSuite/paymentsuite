<?php

/**
 * StripeBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Denys Pasishnyi <dpcat237@gmail.com>
 * @package StripeBundle
 *
 * Denys Pasishnyi 2013
 */

namespace dpcat237\StripeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\CartWrapperInterface;

/**
 * Type for a shop edit profile form
 */
class StripeType extends AbstractType
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
     * Build form function
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('credit_cart', 'text', array(
                'required' => true,
                'max_length' => 20,
            ))
            ->add('credit_cart_security', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))
            ->add('credit_cart_expiration_month', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1, 12), range(1, 12)),
            ))
            ->add('credit_cart_expiration_year', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(2013, 2025), range(2013, 2025)),
            ))
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->cartWrapper->getAmount(), 2) * 100
            ))
            ->add('api_token', 'hidden', array(
                'data'  =>  ''
            ));
    }


    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'stripe_view';
    }
}