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

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Exception\CreateFormException;
use PaymentSuite\PaylandsBundle\Form\Type\PaylandsType;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PaylandsFormFactory.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * PaylandsFormFactory constructor.
     *
     * @param FormFactoryInterface  $formFactory
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Creates the payment form.
     *
     * @param mixed $data
     * @param array $options
     *
     * @return FormInterface
     *
     * @throws CreateFormException
     */
    public function create($data = null, array $options = [])
    {
        $options = array_merge($options, [
            'action' => $this->urlGenerator->generate('paymentsuite_paylands_execute'),
            'method' => 'POST',
        ]);

        if (is_array($data)) {
            $data = $this
                ->formFactory
                ->create(PaylandsType::class)
                ->submit($data, true)
                ->getData();
        }

        if (is_null($data) || $data instanceof PaylandsMethod) {
            return $this->formFactory->create(PaylandsType::class, $data, $options);
        }

        throw new CreateFormException();
    }
}
