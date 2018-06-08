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

use PaymentSuite\PaylandsBundle\Exception\CardNotFoundException;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use WAM\Paylands\ClientInterface;

/**
 * Class PaylandsApiAdapter
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsApiAdapter
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var PaymentBridgeInterface
     */
    private $paymentBridge;
    /**
     * @var PaylandsCurrencyServiceResolver
     */
    private $currencyServiceResolver;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * PaylandsApiAdapter constructor.
     *
     * @param ClientInterface $client
     * @param PaymentBridgeInterface $paymentBridge
     * @param PaylandsCurrencyServiceResolver $currencyServiceResolver
     * @param RequestStack $requestStack
     */
    public function __construct(
        ClientInterface $client,
        PaymentBridgeInterface $paymentBridge,
        PaylandsCurrencyServiceResolver $currencyServiceResolver,
        RequestStack $requestStack
    )
    {
        $this->client = $client;
        $this->paymentBridge = $paymentBridge;
        $this->currencyServiceResolver = $currencyServiceResolver;
        $this->requestStack = $requestStack;
    }

    /**
     * Sends the payment order to Paylands.
     *
     * @param PaylandsMethod $paymentMethod
     */
    public function createTransaction(PaylandsMethod $paymentMethod)
    {
        $paymentOrder = $this->client->createPayment(
            $paymentMethod->getCustomerExternalId(),
            $this->paymentBridge->getAmount(),
            (string)$this->paymentBridge->getOrder(),
            $this->currencyServiceResolver->getService()
        );

        $transaction = $this->client->directPayment(
            $this->requestStack->getMasterRequest()->getClientIp(),
            $paymentOrder['order']['uuid'],
            $paymentMethod->getCardUuid()
        );

        $paymentMethod
            ->setPaymentStatus($transaction['order']['paid'] ? PaylandsMethod::STATUS_OK : PaylandsMethod::STATUS_KO)
            ->setPaymentResult($transaction);
    }

    /**
     * Validates against Paylands that the card is associates with customer.
     *
     * @param PaylandsMethod $paymentMethod
     *
     * @throws CardNotFoundException
     */
    public function validateCard(PaylandsMethod $paymentMethod)
    {
        $response = $this->client->retrieveCustomerCards($paymentMethod->getCustomerExternalId());

        foreach ($response['cards'] as $card) {
            if ($paymentMethod->getCardUuid() == $card['uuid']) {
                $paymentMethod
                    ->setPaymentStatus(PaylandsMethod::STATUS_OK)
                    ->setPaymentResult($response);

                return;
            }
        }

        throw new CardNotFoundException(sprintf('Card %s not found for customer %s',
            $paymentMethod->getCardUuid(),
            $paymentMethod->getCustomerExternalId()
        ));
    }
}