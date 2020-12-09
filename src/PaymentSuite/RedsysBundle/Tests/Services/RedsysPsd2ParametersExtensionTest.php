<?php

namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysPsd2CompliantInterface;
use PaymentSuite\RedsysBundle\Services\Redsys3dSecureBuilder;
use PaymentSuite\RedsysBundle\Services\RedsysPsd2ParametersExtension;
use PHPUnit\Framework\TestCase;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
class RedsysPsd2ParametersExtensionTest extends TestCase
{
    public function testExtendsParametersIfBridgeIsPsd2Compliant()
    {
        $bridge = $this->getPsd2CompliantBridge();

        $parameters = [];

        (new RedsysPsd2ParametersExtension($bridge))->extend($parameters);

        $this->assertArrayHasKey('DS_MERCHANT_EMV3DS', $parameters);
    }

    public function testExtendsParametersIfBridgeIsNotPsd2Compliant()
    {
        $bridge = $this->getBridge();

        $parameters = [];

        (new RedsysPsd2ParametersExtension($bridge))->extend($parameters);

        $this->assertArrayNotHasKey('DS_MERCHANT_EMV3DS', $parameters);
    }

    private function getBridge(): PaymentBridgeInterface
    {
        return new class implements PaymentBridgeInterface {

            public function setOrder($order) {}

            public function getOrder() {}

            public function findOrder($orderId) {}

            public function getOrderId() {}

            public function isOrderPaid() {}

            public function getAmount() {}

            public function getCurrency() {}

            public function getExtraData() {}
        };
    }

    private function getPsd2CompliantBridge(): RedsysPsd2CompliantInterface
    {
        return new class implements PaymentBridgeInterface, RedsysPsd2CompliantInterface {

            public function setOrder($order) {}

            public function getOrder() {}

            public function findOrder($orderId) {}

            public function getOrderId() {}

            public function isOrderPaid() {}

            public function getAmount() {}

            public function getCurrency() {}

            public function getExtraData() {}

            public function build3dSecureParameters(Redsys3dSecureBuilder $builder): void
            {
                $builder->addCardholderName('Test');
            }
        };
    }
}
