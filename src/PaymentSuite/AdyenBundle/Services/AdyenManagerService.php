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

use Symfony\Bridge\Monolog\Logger;
use PaymentSuite\AdyenBundle\AdyenMethod;
use Doctrine\Common\Persistence\ObjectManager;
use PaymentSuite\AdyenBundle\Entity\Transaction;
use Doctrine\Common\Persistence\ObjectRepository;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\AdyenBundle\Interfaces\PaymentBridgeAdyenInterface;
use Mascoteros\Checkout\Application\Command\PS2ValidationCommandInterface;

/**
 * Class AdyenManagerService
 * @package PaymentSuite\AdyenBundle\Service
 */
class AdyenManagerService
{
    const REFUSED = 'Refused';
    const AUTHORISED = 'Authorised';
    const IDENTIFY_SHOPPER = 'IdentifyShopper';
    const CHALLENGE_SHOPPER = 'ChallengeShopper';
    const REDIRECT_SHOPPER = 'RedirectShopper';

    /**
     * @var string
     */
    protected $merchantCode;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var PaymentEventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var AdyenClientService
     */
    private $adyenClientService;

    /**
     * @var PaymentBridgeAdyenInterface
     */
    private $paymentBridge;

    /**
     * @var ObjectManager
     */
    private $transactionObjectManager;

    /**
     * @var ObjectRepository
     */
    private $transactionRepository;

    /**
     * @var array
     */
    private $browserInfo;

    /**
     * AdyenService constructor.
     *
     * @param PaymentEventDispatcher $eventDispatcher
     * @param PaymentBridgeAdyenInterface $paymentBridge
     * @param ObjectManager $transactionObjectManager
     * @param ObjectRepository $transactionRepository
     * @param AdyenClientService $adyenClientService ,
     * @param Logger $logger ,
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
     * @param PS2AppValidationCommand $ps2ValidationData
     * @return mixed
     *
     * @throws PaymentException
     */
    public function process3DSValidation(
        PaymentMethodInterface $method,
        $amount,
        PS2ValidationCommandInterface $ps2ValidationData
    ) {
        if ($ps2ValidationData->isAppRequest()) {
            $paymentData = $this->setAppRequestData($ps2ValidationData);
        } else {
            $paymentData = $this->setBrowserCommonData(
                $ps2ValidationData->getNotificationUrl(),
                [],
                $ps2ValidationData->getBrowserInfo()
            );

            if ($ps2ValidationData->isChallengeTransaction()) {
                if ($ps2ValidationData->getTransStatus()) {
                    $paymentData['threeDS2Result']['transStatus'] = 'Y';
                } else {
                    $paymentData['threeDS2Result']['transStatus'] = 'U';
                }
            }
        }

        $paymentData['merchantAccount'] = $this->merchantCode;
        $paymentData['threeDS2Token'] = $ps2ValidationData->getThreeDS2Token();

        return $this->callValidate3DS2($method, $amount, true, $paymentData);
    }

    /**
     * @param PaymentMethodInterface $method
     * @param integer $amount
     * @param bool $ps2Available
     * @param bool $isAppRequest
     * @param null $notificationUrl
     * @param array $browserInfo
     *
     * @return mixed
     *
     * @throws PaymentException
     */
    public function processPayment(
        PaymentMethodInterface $method,
        $amount,
        $ps2Available = false,
        $isAppRequest = false,
        $notificationUrl = null,
        array $browserInfo = []
    ) {
        /**
         * @var AdyenMethod $method
         */
        $paymentData = [];

        $paymentData['additionalData'] = [
            'card.encrypted.json' => $method->getAdditionalData()
        ];

        if ($ps2Available) {
            $paymentData['additionalData']['allow3DS2'] = true;

            if ($isAppRequest) {
                $paymentData['threeDS2RequestData']['deviceChannel'] = 'app';
            } else {
                $paymentData = $this->setBrowserCommonData($notificationUrl, $paymentData, $browserInfo);
            }
        }

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

        return $this->callPayment($method, $amount, $ps2Available, $paymentData);
    }

    /**
     * @param $response
     * @return bool
     */
    private function isAuthorized($response)
    {
        if (isset($response['resultCode']) && $response['resultCode'] == AdyenManagerService::AUTHORISED) {
            return true;
        }

        return false;
    }

    /**
     * @param $response
     * @return bool
     */
    private function isRefused($response)
    {
        if (isset($response['resultCode']) && $response['resultCode'] == AdyenManagerService::REFUSED) {
            return true;
        }

        return false;
    }

    /**
     * @param $response
     * @return bool
     */
    private function isIdentifyShopper($response)
    {
        if (isset($response['resultCode']) && $response['resultCode'] == AdyenManagerService::IDENTIFY_SHOPPER) {
            return true;
        }

        return false;
    }

    /**
     * @param $response
     * @return bool
     */
    private function isChallengeShopper($response)
    {
        if (isset($response['resultCode']) && $response['resultCode'] == AdyenManagerService::CHALLENGE_SHOPPER) {
            return true;
        }

        return false;
    }

    /**
     * @param $response
     * @return bool
     */
    private function isRedirectShopper($response)
    {
        if (isset($response['resultCode']) && $response['resultCode'] == AdyenManagerService::REDIRECT_SHOPPER) {
            return true;
        }

        return false;
    }

    /**
     * @param $response
     *
     * @throws \Exception
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

    /**
     * @param $shopperReference
     * @param $contract
     *
     * @return mixed
     *
     * @throws \Adyen\AdyenException
     */
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

    /**
     * @param $paymentData
     * @return mixed
     * @throws \Adyen\AdyenException
     */
    protected function doRecurring($paymentData)
    {
        $paymentService = $this->adyenClientService->getRecurringService();

        return $paymentService->listRecurringDetails($paymentData);
    }

    /**
     * @param $shopperReference
     * @param $recurringDetailReference
     * @param $contract
     * @return mixed
     */
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

    /**
     * @param PaymentMethodInterface $method
     * @param $amount
     * @param $ps2Available
     * @param array $paymentData
     * @return mixed
     * @throws PaymentException
     */
    private function callValidate3DS2(PaymentMethodInterface $method, $amount, $ps2Available, array $paymentData)
    {
        try {
            $r = $this->callValidate3DS2Api($paymentData);
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

            $this->logger->addError('PaymentException: ' . $e->getMessage());
            $this->paymentBridge->setError($e->getMessage());
            $this->paymentBridge->setErrorCode($e->getCode());
            throw new PaymentException($e->getMessage());
        }

        return $this->processPaymentResponse($method, $amount, $ps2Available, $r);
    }

    /**
     * @param PaymentMethodInterface $method
     * @param $amount
     * @param $ps2Available
     * @param array $paymentData
     * @return mixed
     * @throws PaymentException
     */
    private function callPayment(PaymentMethodInterface $method, $amount, $ps2Available, array $paymentData)
    {
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

            $this->logger->addError('PaymentException: ' . $e->getMessage());
            $this->paymentBridge->setError($e->getMessage());
            $this->paymentBridge->setErrorCode($e->getCode());
            throw new PaymentException($e->getMessage());
        }

        return $this->processPaymentResponse($method, $amount, $ps2Available, $r);
    }

    /**
     * @param PaymentMethodInterface $method
     * @param $amount
     * @param $ps2Available
     * @param $r
     * @return mixed
     * @throws PaymentException
     */
    private function processPaymentResponse(PaymentMethodInterface $method, $amount, $ps2Available, $r)
    {
        $r['amount'] = $amount;
        $this->storeTransaction($r);

        $transactionError = false;

        if (!$ps2Available) {
            // When not a 3DS2 transaction only can be authorized or refused
            if (!$this->isAuthorized($r)) {
                $transactionError = true;
            }
        } else {
            // Transaction can be authorized (so we must create the order) or redirect to next step
            if ($this->isIdentifyShopper($r) || $this->isChallengeShopper($r) || $this->isRedirectShopper($r)) {
                // We need to send response to application to show next step to the user
                return $r;
            } else if (!$this->isAuthorized($r)) {
                $transactionError = true;
            }
        }

        // An error occurred with not 3DS2 or 3DS2 transaction so return the error
        if ($transactionError) {
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

        return $r;
    }

    /**
     * @param $paymentData
     * @return mixed
     * @throws \Adyen\AdyenException
     */
    private function callApi($paymentData)
    {
        $paymentService = $this->adyenClientService->getPaymentService();

        return $paymentService->authorise($paymentData);

    }

    /**
     * @param $paymentData
     * @return mixed
     * @throws \Adyen\AdyenException
     */
    private function callValidate3DS2Api($paymentData)
    {
        $paymentService = $this->adyenClientService->getPaymentService();

        return $paymentService->authorise3DS2($paymentData);
    }

    /**
     * @param PS2ValidationCommandInterface $ps2ValidationData
     *
     * @return array
     */
    private function setAppRequestData(PS2ValidationCommandInterface $ps2ValidationData): array
    {
        $paymentData = [];

        if ('fingerPrint' == $ps2ValidationData->getValidationType()) {
            $paymentData["threeDS2RequestData"] = [
                "deviceChannel" => "app",
                "sdkAppID" => $ps2ValidationData->getSdkAppId(),
                "sdkEncData" => $ps2ValidationData->getSdkEncData(),
                "sdkEphemPubKey" => [
                    "crv" => $ps2ValidationData->getSdkEphemPubKey()['crv'],
                    "kty" => $ps2ValidationData->getSdkEphemPubKey()['kty'],
                    "x" => $ps2ValidationData->getSdkEphemPubKey()['x'],
                    "y" => $ps2ValidationData->getSdkEphemPubKey()['y']
                ],
                "sdkReferenceNumber" => $ps2ValidationData->getSdkReferenceNumber(),
                "sdkTransID" => $ps2ValidationData->getSdkTransID()
            ];
        } else {
            $paymentData["threeDS2Result"] = [
                "deviceChannel" => "app",
                "transStatus" => $ps2ValidationData->getTransactionStatus()
            ];
        }

        return $paymentData;
    }

    /**
     * @param $notificationUrl
     * @param array $paymentData
     * @param array $browserInfo
     *
     * @return array
     */
    private function setBrowserCommonData(
        $notificationUrl,
        array $paymentData = [],
        array $browserInfo = []
    ) {
        $paymentData['threeDS2RequestData']['deviceChannel'] = 'browser';
        $paymentData['threeDS2RequestData']['notificationURL'] = $notificationUrl;
        $paymentData['threeDS2RequestData']['threeDSCompInd'] = 'N';

        $paymentData['browserInfo']['userAgent'] = $browserInfo['user_agent'];
        $paymentData['browserInfo']['acceptHeader'] = "text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/webp,image\/apng,*\/*;q=0.8";
        $paymentData['browserInfo']['language'] = "es";
        $paymentData['browserInfo']['colorDepth'] = 24;
        $paymentData['browserInfo']['screenHeight'] = 723;
        $paymentData['browserInfo']['screenWidth'] = 1536;
        $paymentData['browserInfo']['timeZoneOffset'] = '+60';
        $paymentData['browserInfo']['javaEnabled'] = $browserInfo['is_java_enabled'];

        return $paymentData;
    }
}
