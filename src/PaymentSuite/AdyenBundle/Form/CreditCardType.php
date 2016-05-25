<?php

/*
 * This file is part of the Mascoteros package.
 *
 * Copyright (c) 2015-2016 Mascoteros.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */
namespace PaymentSuite\AdyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

/**
 * Type for a shop edit profile form
 */
class CreditCardType extends AbstractType
{
    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * Form type construct method
     *
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
        ));
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
            ->add('credit_card_owner', 'text', array(
                'required' => false,
            ))
            ->add('credit_card', 'text', array(
                'max_length' => 20,
                'required' => false,
            ))
            ->add('credit_card_security', 'text', array(
                'required' => false,
                'max_length' => 4,
            ))
            ->add('credit_card_expiration_month', 'choice', array(
                'required' => false,
                'choices' => array_combine(range(1, 12), range(1, 12)),
            ))
            ->add('credit_card_expiration_year', 'choice', array(
                'required' => false,
                'choices' => array_combine(range(date('Y'), 2025), range(date('Y'), 2025)),
            ))
            ->add('amount', 'hidden', array(
                'required' => true,
                'data'  =>  $this->paymentBridge->getAmount(),
            ))
            ->add('additionalData', 'hidden', array(
                'required' => false,
            ))
            ->add('generationDate', 'hidden', array(
                'required' => false,
                'attr' => [
                    'data-encrypted-name' => 'generationtime'
                ]
            ))
            ;


    }

    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'adyen_credit_card_form_type';
    }
}
