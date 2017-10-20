<?php

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Exception\CardNotFoundException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaylandsBundle\ApiClient\ApiClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PaylandsManager.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsManager
{
    const STATUS_OK = 'OK';

    const STATUS_KO = 'KO';

    /**
     * @var PaymentBridgeInterface
     *
     * Payment Bridge
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    private $paymentEventDispatcher;

    /**
     * @var ApiClientInterface
     *
     * Paylands API client
     */
    private $apiClient;

    /**
     * @var RequestStack
     *
     * Http request stack
     */
    private $requestStack;

    /**
     * PaylandsManager constructor.
     *
     * @param PaymentBridgeInterface $paymentBridge
     * @param PaymentEventDispatcher $paymentEventDispatcher
     * @param ApiClientInterface     $apiClient
     * @param RequestStack           $requestStack
     */
    public function __construct(
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher,
        ApiClientInterface $apiClient,
        RequestStack $requestStack
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->apiClient = $apiClient;
        $this->requestStack = $requestStack;
    }

    /**
     * Tries to process a payment through Paylands.
     *
     * @param PaylandsMethod $paymentMethod Payment method
     *
     * @return PaylandsManager Self object
     *
     * @throws PaymentException
     */
    public function processPayment(PaylandsMethod $paymentMethod)
    {
        /*
         * At this point, order must be created given a cart, and placed in PaymentBridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $paymentMethod
            );

        /*
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /*
         * Order exists right here.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paymentMethod
            );

        /*
         * Try to make the payment transaction
         */
        try {
            $this->validateCard($paymentMethod);

            if (!$paymentMethod->isOnlyTokenizeCard()) {
                $this->createTransaction($paymentMethod);
            }

            /*
             * Payment paid done.
             *
             * Paid process has ended ( No matters result )
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderDone(
                    $this->paymentBridge,
                    $paymentMethod
                );

            if ($paymentMethod->getPaymentStatus() !== self::STATUS_OK) {
                throw new PaymentException(sprintf('Order %s could not be paid',
                    $paymentMethod->getPaymentResult()['order']['uuid']
                ));
            }

            /*
             * Payment paid successfully.
             *
             * Paid process has ended successfully
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderSuccess(
                    $this->paymentBridge,
                    $paymentMethod
                );
        } catch (PaymentException $e) {

            /*
             * Payment paid failed.
             *
             * Paid process has ended failed
             */
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $paymentMethod
                );

            throw $e;
        }

        return $this;
    }

    /**
     * Sends the payment order to Paylands.
     *
     * @param PaylandsMethod $paymentMethod
     */
    private function createTransaction(PaylandsMethod $paymentMethod)
    {
        $paymentOrder = $this->apiClient->createPayment(
            $paymentMethod->getCustomerExternalId(),
            $this->paymentBridge->getAmount(),
            (string) $this->paymentBridge->getOrder()
        );

        $transaction = $this->apiClient->directPayment(
            $this->requestStack->getMasterRequest()->getClientIp(),
            $paymentOrder['order']['uuid'],
            $paymentMethod->getCardUuid()
        );

        $paymentMethod
            ->setPaymentStatus($transaction['order']['paid'] ? self::STATUS_OK : self::STATUS_KO)
            ->setPaymentResult($transaction);
    }

    /**
     * Validates against Paylands that the card is associates with customer.
     *
     * @param PaylandsMethod $paymentMethod
     *
     * @throws CardNotFoundException
     */
    private function validateCard(PaylandsMethod $paymentMethod)
    {
        $response = $this->apiClient->retrieveCustomerCards($paymentMethod->getCustomerExternalId());

        foreach ($response['cards'] as $card) {
            if ($paymentMethod->getCardUuid() == $card['uuid']) {
                $paymentMethod
                    ->setPaymentStatus(self::STATUS_OK)
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
