<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\StripeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

/**
 * Type for a shop edit profile form.
 */
class StripeType extends AbstractType
{
    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * Form type construct method.
     *
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }

    /**
     * Build form function.
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('credit_card', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxlength' => 20,
                ]
            ])
            ->add('credit_card_security', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxlength' => 4,
                ]
            ])
            ->add('credit_card_expiration_month', ChoiceType::class, [
                'required' => true,
                'choices' => array_combine(range(1, 12), range(1, 12)),
            ])
            ->add('credit_card_expiration_year', ChoiceType::class, [
                'required' => true,
                'choices' => array_combine(range(date('Y'), 2025), range(date('Y'), 2025)),
            ])
            ->add('amount', HiddenType::class, [
                'data' => $this->paymentBridge->getAmount(),
            ])
            ->add('api_token', HiddenType::class, [
                'data' => '',
            ]);
    }

    /**
     * Return unique name for this form.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'stripe_view';
    }
}
