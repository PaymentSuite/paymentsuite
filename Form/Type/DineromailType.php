<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author David Pujadas <dpujadas@gmail.com>
 * @package DineromailBundle
 *
 * David Pujadas 2013
 */

namespace Dpujadas\DineromailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\CartWrapperInterface;

/**
 * Type for a shop edit profile form
 */
class DineromailType extends AbstractType
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
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->cartWrapper->getAmount(), 2) * 100
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
        return 'dineromail_view';
    }
}