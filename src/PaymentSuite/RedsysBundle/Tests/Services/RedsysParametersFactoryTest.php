<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysOrderTransformerInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysParametersExtensionInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysSettingsProviderInterface;
use PaymentSuite\RedsysBundle\Services\RedsysParametersFactory;
use PaymentSuite\RedsysBundle\Services\RedsysUrlFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
class RedsysParametersFactoryTest extends TestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $paymentBridge;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $urlFactory;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $orderTransformer;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $settingsProvider;

    protected function setUp()
    {
        $this->paymentBridge = $this->prophesize(PaymentBridgeRedsysInterface::class);
        $this->paymentBridge->getOrderId()->willReturn(1);
        $this->paymentBridge->getAmount()->willReturn(100);
        $this->paymentBridge->getCurrency()->willReturn('EUR');
        $this->paymentBridge->getExtraData()->willReturn([]);
        $this->urlFactory = $this->prophesize(RedsysUrlFactory::class);
        $this->urlFactory->getReturnRedsysUrl()->willReturn('redsys-url');
        $this->urlFactory->getReturnUrlOkForOrderId(1)->willReturn('redsys-url-ok');
        $this->urlFactory->getReturnUrlKoForOrderId(1)->willReturn('redsys-url-ko');
        $this->orderTransformer = $this->prophesize(RedsysOrderTransformerInterface::class);
        $this->orderTransformer->transform(1)->willReturn('transformed-order');
        $this->settingsProvider = $this->prophesize(RedsysSettingsProviderInterface::class);
        $this->settingsProvider->getMerchanCode()->willReturn('merchant-code');
        $this->settingsProvider->getTerminal()->willReturn('terminal-code');
    }

    public function testCreateWithoutExtensions()
    {
        $parameters = $this->getFactory()->create();

        $this->assertEquals(
            [
                'Ds_Merchant_TransactionType' => 0,
                'Ds_Merchant_MerchantURL' => 'redsys-url',
                'Ds_Merchant_UrlOK' => 'redsys-url-ok',
                'Ds_Merchant_UrlKO' => 'redsys-url-ko',
                'Ds_Merchant_Amount' => 100,
                'Ds_Merchant_Order' => 'transformed-order',
                'Ds_Merchant_MerchantCode' => 'merchant-code',
                'Ds_Merchant_Currency' => '978',
                'Ds_Merchant_Terminal' => 'terminal-code',
            ],
            $parameters
        );
    }

    public function testCreateWithExtensions()
    {
        $factory = $this->getFactory();
        $factory->addExtension(new class implements RedsysParametersExtensionInterface {
            public function extend(array &$parameters): void
            {
                $parameters['Extended'] = 'test';
            }
        });

        $parameters = $factory->create();

        $this->assertEquals(
            [
                'Ds_Merchant_TransactionType' => 0,
                'Ds_Merchant_MerchantURL' => 'redsys-url',
                'Ds_Merchant_UrlOK' => 'redsys-url-ok',
                'Ds_Merchant_UrlKO' => 'redsys-url-ko',
                'Ds_Merchant_Amount' => 100,
                'Ds_Merchant_Order' => 'transformed-order',
                'Ds_Merchant_MerchantCode' => 'merchant-code',
                'Ds_Merchant_Currency' => '978',
                'Ds_Merchant_Terminal' => 'terminal-code',
                'Extended' => 'test',
            ],
            $parameters
        );
    }

    public function testCreateWithExtraData()
    {
        $this->paymentBridge->getExtraData()->willReturn([
            'product_description' => 'Product description',
            'merchant_titular' => 'Merchant titular',
            'merchant_name' => 'Merchant name',
            'merchant_data' => 'Merchant data',
        ]);

        $parameters = $this->getFactory()->create();

        $this->assertEquals(
            [
                'Ds_Merchant_TransactionType' => 0,
                'Ds_Merchant_MerchantURL' => 'redsys-url',
                'Ds_Merchant_UrlOK' => 'redsys-url-ok',
                'Ds_Merchant_UrlKO' => 'redsys-url-ko',
                'Ds_Merchant_Amount' => 100,
                'Ds_Merchant_Order' => 'transformed-order',
                'Ds_Merchant_MerchantCode' => 'merchant-code',
                'Ds_Merchant_Currency' => '978',
                'Ds_Merchant_Terminal' => 'terminal-code',
                'Ds_Merchant_ProductDescription' => 'Product description',
                'Ds_Merchant_Titular' => 'Merchant titular',
                'Ds_Merchant_MerchantName' => 'Merchant name',
                'Ds_Merchant_MerchantData' => 'Merchant data',
            ],
            $parameters
        );
    }

    private function getFactory(): RedsysParametersFactory
    {
        return new RedsysParametersFactory(
            $this->paymentBridge->reveal(),
            $this->urlFactory->reveal(),
            $this->orderTransformer->reveal(),
            $this->settingsProvider->reveal()
        );
    }
}
