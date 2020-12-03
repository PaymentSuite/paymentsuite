<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysOrderTransformerInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysParametersExtensionInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysParametersFactoryInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysSettingsProviderInterface;
use PaymentSuite\RedsysBundle\Util\CurrencyNumber;

class RedsysParametersFactory implements RedsysParametersFactoryInterface
{
    /**
     * @var PaymentBridgeRedsysInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var RedsysUrlFactory
     *
     * URL Factory service
     */
    private $urlFactory;

    /**
     * @var RedsysOrderTransformerInterface
     */
    private $redsysOrderTransformer;

    /**
     * @var RedsysSettingsProviderInterface
     */
    private $settingsProvider;

    /**
     * @var RedsysParametersExtensionInterface[]
     */
    private $extensions = [];

    public function __construct(
        PaymentBridgeRedsysInterface $paymentBridge,
        RedsysUrlFactory $urlFactory,
        RedsysOrderTransformerInterface $redsysOrderTransformer,
        RedsysSettingsProviderInterface $redsysSettingsProvider
    ) {
        $this->paymentBridge = $paymentBridge;
        $this->urlFactory = $urlFactory;
        $this->redsysOrderTransformer = $redsysOrderTransformer;
        $this->settingsProvider = $redsysSettingsProvider;
    }

    /**
     * @return array
     *
     * @throws CurrencyNotSupportedException
     */
    public function create(): array
    {
        $orderId = $this->paymentBridge->getOrderId();

        $extraData = $this->paymentBridge->getExtraData();

        $parameters = [
            'Ds_Merchant_TransactionType' => isset($extraData['transaction_type']) ? $extraData['transaction_type'] : 0,
            'Ds_Merchant_MerchantURL' => $this->urlFactory->getReturnRedsysUrl(),
            'Ds_Merchant_UrlOK' => $this->urlFactory->getReturnUrlOkForOrderId($orderId),
            'Ds_Merchant_UrlKO' => $this->urlFactory->getReturnUrlKoForOrderId($orderId),
            'Ds_Merchant_Amount' => (string) $this->paymentBridge->getAmount(),
            'Ds_Merchant_Order' => $this->redsysOrderTransformer->transform($orderId),
            'Ds_Merchant_MerchantCode' => $this->settingsProvider->getMerchanCode(),
            'Ds_Merchant_Currency' => CurrencyNumber::fromCode($this->paymentBridge->getCurrency()),
            'Ds_Merchant_Terminal' => $this->settingsProvider->getTerminal(),
        ];

        if (array_key_exists('product_description', $extraData)) {
            $parameters['Ds_Merchant_ProductDescription'] = $extraData['product_description'];
        }

        if (array_key_exists('merchant_titular', $extraData)) {
            $parameters['Ds_Merchant_Titular'] = $extraData['merchant_titular'];
        }

        if (array_key_exists('merchant_name', $extraData)) {
            $parameters['Ds_Merchant_MerchantName'] = $extraData['merchant_name'];
        }

        if (array_key_exists('merchant_data', $extraData)) {
            $parameters['Ds_Merchant_MerchantData'] = $extraData['merchant_data'];
        }

        $this->applyExtensions($parameters);

        return $parameters;
    }

    public function addExtension(RedsysParametersExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    private function applyExtensions(array &$parameters): void
    {
        foreach ($this->extensions as $extension) {
            $extension->extend($parameters);
        }
    }
}
