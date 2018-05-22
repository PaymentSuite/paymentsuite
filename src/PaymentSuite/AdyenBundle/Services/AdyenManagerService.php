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
namespace PaymentSuite\AdyenBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Elcodi\Component\Core\Services\ObjectDirector;
use PaymentSuite\AdyenBundle\Interfaces\PaymentBridgeAdyenInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\AdyenBundle\Entity\Transaction;
use PaymentSuite\AdyenBundle\AdyenMethod;
use Symfony\Bridge\Monolog\Logger;
use Adyen\Contract;
use Adyen\Service\Recurring;

/**
 * Class AdyenManagerService
 * @package PaymentSuite\AdyenBundle\Service
 */
class AdyenManagerService
{
    protected $merchantCode;
    protected $currency;

    /**
     * @var Logger
     */
    protected $logger;

    const AUTHORISED = 'Authorised';

    /**
     * AdyenService constructor.
     * @param PaymentEventDispatcher $eventDispatcher
     * @param PaymentBridgeAdyenInterface $paymentBridge
     * @param ObjectManager $transactionObjectManager
     * @param ObjectRepository $transactionRepository
     * @param AdyenClientService $adyenClientService,
     * @param Logger $logger,
     * @param string $merchantCode
     * @param string $currency
     */
    public function __construct(
        PaymentEventDispatcher $eventDispatcher,
        PaymentBridgeAdyenInterface $paymentBridge,
        ObjectManager $transactionObjectManager,
        ObjectRepository $transactionRepository,
        AdyenClientService $adyenClientService,
        Logger $logger,
        $merchantCode,
        $currency
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->transactionObjectManager = $transactionObjectManager;
        $this->transactionRepository = $transactionRepository;
        $this->adyenClientService = $adyenClientService;
        $this->logger = $logger;

        $this->merchantCode = $merchantCode;
        $this->currency = $currency;

    }

    /**
     * @param PaymentMethodInterface $method
     * @param integer $amount
     *
     * @throws PaymentException
     */
    public function processPayment(PaymentMethodInterface $method, $amount)
    {
        /**
         * @var AdyenMethod $method
         */
        $paymentData= [];
        $paymentData['additionalData'] = [
            'card.encrypted.json' => $method->getAdditionalData()
        ];

        $paymentData['amount'] = [
            'value' => $amount,
            'currency' => $this->currency
        ];

        $paymentData['reference'] = $method->getTransactionId();
        $paymentData['merchantAccount'] = $this->merchantCode;

        if ($method->isRecurring() === true) {
            $paymentData['shopperReference'] = $method->getShopperReference();
            $paymentData['recurring'] = [
                'contract' => $method->getContract()
            ];
        }

        if (!empty($method->getRecurringDetailReference())) {
            $paymentData['shopperEmail'] = $method->getShopperEmail();
            $paymentData['shopperInteraction'] = $method->getShopperInteraction();
            $paymentData['selectedRecurringDetailReference'] = $method->getRecurringDetailReference();
        }

        try {
            $r = $this->callApi($paymentData);
        } catch (\Exception $e) {
            /*
             * The Soap call failed
             */
            $this
                ->eventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $method
                );

            $this->logger->addError('PaymentException: '.$e->getMessage());
            $this->paymentBridge->setError($e->getMessage());
            $this->paymentBridge->setErrorCode($e->getCode());
            throw new PaymentException($e->getMessage());
        }

        $r['amount'] = $amount;
        $this->storeTransaction($r);

        if (!$this->isAuthorized($r)) {
            $this->paymentBridge->setError($this->getError($r));
            $this->paymentBridge->setErrorCode($this->getErrorCode($r));

            /**
             * The payment was not successful
             */
            $this
                ->eventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $method
                );

            throw new PaymentException($this->getErrorCode($r));
        }

        $this
            ->eventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $method
            );


        /*
         * Everything is ok, emitting the
         * payment.order.create event
         */
        $method
            ->setTransactionId($r['pspReference'])
            ->setTransactionStatus('paid');

        $this
            ->eventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $method
            );

        /**
         * Payment process has returned control
         */
        $this
            ->eventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $method
            );

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this
            ->eventDispatcher
            ->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $method
            );

    }

    protected function isAuthorized($response)
    {
        if (isset($response['resultCode']) && $response['resultCode'] == AdyenManagerService::AUTHORISED) {
            return true;
        }

        return false;
    }

    protected function callApi($paymentData)
    {
        $paymentService = $this->adyenClientService->getPaymentService();

        return $paymentService->authorise($paymentData);

    }

    /**
     * @param $response
     */
    protected function storeTransaction($response)
    {
        /**
         * this is a RESPONSE for the moment
         */
        $transaction = new Transaction();
        $transaction->setCartId($this->paymentBridge->getCartId());
        $transaction->setAmount($response['amount']);
        $transaction->setCreatedAt(new \DateTime('now'));
        $transaction->setPspReference($response['pspReference']);
        $transaction->setResultCode($response['resultCode']);

        if (isset($response['authCode'])) {
            $transaction->setAuthCode($response['authCode']);
        }
        if (isset($response['refusalReason'])) {
            $transaction->setMessage($response['refusalReason']);
        }

        $this->transactionObjectManager->persist($transaction);
        $this->transactionObjectManager->flush();
    }

    public function getListRecurringDetails($shopperReference, $contract)
    {
        $paymentData = [];
        $paymentData['merchantAccount'] = $this->merchantCode;
        $paymentData['shopperReference'] = $shopperReference;
        $paymentData['recurring'] = [
            'Contract' => $contract
        ];

        return $this->doRecurring($paymentData);
    }

    protected function doRecurring($paymentData)
    {
        $paymentService = $this->adyenClientService->getRecurringService();

        return $paymentService->listRecurringDetails($paymentData);
    }

    public function removeCreditCard($shopperReference, $recurringDetailReference, $contract)
    {
        $paymentData = [];
        $paymentData['merchantAccount'] = $this->merchantCode;
        $paymentData['shopperReference'] = $shopperReference;
        $paymentData['recurringDetailReference'] = $recurringDetailReference;
        $paymentData['recurring'] = [
            'Contract' => $contract
        ];

        return $this->doDisableCreditCard($paymentData);
    }

    protected function doDisableCreditCard($paymentData)
    {
        $paymentService = $this->adyenClientService->getRecurringService();

        return $paymentService->disable($paymentData);
    }

    protected function getError($response)
    {
        if (isset($response['refusalReason'])) {
            return $response['refusalReason'];
        }
    }

    protected function getErrorCode($response)
    {
        if (isset($response['resultCode'])) {
            return $response['resultCode'];
        }
    }

}
