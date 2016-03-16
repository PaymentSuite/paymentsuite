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

namespace PaymentSuite\PaylandsBundle\Form\Type;

use PaymentSuite\PaylandsBundle\PaylandsMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaylandsType.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customerExternalId', HiddenType::class, [
                'required' => true,
            ])
            ->add('customerToken', HiddenType::class, [
                'required' => true,
            ])
            ->add('cardBin', HiddenType::class, [
                'attr' => [
                    'data-source' => 'bin',
                ],
            ])
            ->add('cardBrand', HiddenType::class, [
                'attr' => [
                    'data-source' => 'brand',
                ],
            ])
            ->add('cardCountry', HiddenType::class, [
                'attr' => [
                    'data-source' => 'country',
                ],
            ])
            ->add('cardExpireMonth', HiddenType::class, [
                'attr' => [
                    'data-source' => 'expire_month',
                ],
            ])
            ->add('cardExpireYear', HiddenType::class, [
                'attr' => [
                    'data-source' => 'expire_year',
                ],
            ])
            ->add('cardLast4', HiddenType::class, [
                'attr' => [
                    'data-source' => 'last4',
                ],
            ])
            ->add('cardType', HiddenType::class, [
                'attr' => [
                    'data-source' => 'type',
                ],
            ])
            ->add('cardUuid', HiddenType::class, [
                'required' => true,
                'attr' => [
                    'data-source' => 'uuid',
                ],
            ])
            ->add('cardAdditional', HiddenType::class, [
                'attr' => [
                    'data-source' => 'additional',
                ],
            ])
            ->add('onlyTokenizeCard', HiddenType::class, [
                'required' => true,
            ])
            ->add('validate_button', ButtonType::class, [
                'label' => 'paylands.label.validate_button',
                'translation_domain' => 'PaylandsBundle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaylandsMethod::class,
        ]);
    }
}
