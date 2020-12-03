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

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysOrderTransformerInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysParametersFactoryInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysSettingsProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;

/**
 * RedsysFormTypeBuilder.
 */
class RedsysFormTypeBuilder
{
    /**
     * @var PaymentBridgeRedsysInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

    /**
     * @var RedsysSignatureFactory
     */
    private $signatureFactory;

    /**
     * @var string
     */
    private $url;

    /**
     * @var RedsysParametersFactoryInterface
     */
    private $parametersFactory;

    /**
     * construct.
     *
     * @param PaymentBridgeRedsysInterface $paymentBridge Payment bridge
     * @param RedsysSignatureFactory $signatureFactory Signature factory service
     * @param FormFactory $formFactory Form factory
     * @param RedsysParametersFactoryInterface $parametersFactory
     * @param string $url gateway url
     */
    public function __construct(
        PaymentBridgeRedsysInterface $paymentBridge,
        RedsysSignatureFactory $signatureFactory,
        FormFactory $formFactory,
        RedsysParametersFactoryInterface $parametersFactory,
        $url
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->formFactory = $formFactory;
        $this->url = $url;
        $this->signatureFactory = $signatureFactory;
        $this->parametersFactory = $parametersFactory;
    }

    /**
     * Builds form given return, success and fail urls.
     *
     * @return FormView
     *
     * @throws CurrencyNotSupportedException
     */
    public function buildForm()
    {
        $merchantParameters = $this->parametersFactory->create();

        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $formBuilder
            ->setAction($this->url)
            ->setMethod('POST')
            ->add('Ds_SignatureVersion', HiddenType::class, array(
                'data' => 'HMAC_SHA256_V1',
            ))
            ->add('Ds_MerchantParameters', HiddenType::class, array(
                'data' => RedsysEncoder::encode($merchantParameters),
            ))
            ->add('Ds_Signature', HiddenType::class, array(
                'data' => (string) $this->signatureFactory->createFromMerchantParameters($merchantParameters),
            ));

        return $formBuilder
            ->getForm()
            ->createView();
    }
}
