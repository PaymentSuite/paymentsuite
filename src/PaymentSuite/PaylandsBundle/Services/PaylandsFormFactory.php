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

use PaymentSuite\PaylandsBundle\Form\Type\PaylandsType;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaylandsBundle\Services\Interfaces\PaylandsSettingsProviderInterface;
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
     * @var PaylandsSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * PaylandsFormFactory constructor.
     *
     * @param FormFactoryInterface              $formFactory
     * @param UrlGeneratorInterface             $urlGenerator
     * @param PaylandsSettingsProviderInterface $settingsProvider
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        PaylandsSettingsProviderInterface $settingsProvider
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->settingsProvider = $settingsProvider;
    }

    /**
     * Creates the payment form.
     *
     * @param PaylandsMethod $data
     *
     * @return FormInterface
     */
    private function create(PaylandsMethod $data)
    {
        $options = [
            'action' => $this->urlGenerator->generate('paymentsuite_paylands_execute'),
            'method' => 'POST',
        ];

        return $this->formFactory->create(PaylandsType::class, $data, $options);
    }

    /**
     * @return FormInterface
     */
    public function createEmpty()
    {
        $paymentMethod = new PaylandsMethod($this->settingsProvider->getPaymentName());

        return $this->create($paymentMethod);
    }

    /**
     * @param string $customerExternalId
     * @param string $customerToken
     * @param bool   $onlyTokenizeCard
     *
     * @return FormInterface
     */
    public function createForTransaction($customerExternalId, $customerToken, $onlyTokenizeCard)
    {
        $paymentMethod = new PaylandsMethod($this->settingsProvider->getPaymentName());

        $paymentMethod
            ->setCustomerExternalId($customerExternalId)
            ->setCustomerToken($customerToken)
            ->setOnlyTokenizeCard($onlyTokenizeCard);

        return $this->create($paymentMethod);
    }
}
