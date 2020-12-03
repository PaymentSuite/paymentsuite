<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysParametersExtensionInterface;
use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysPsd2CompliantInterface;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
class RedsysPsd2ParametersExtension implements RedsysParametersExtensionInterface
{
    private $paymentBridge;

    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }

    public function extend(array &$parameters): void
    {
        if (!$this->paymentBridge instanceof RedsysPsd2CompliantInterface) {
            return;
        }

        $secureBuilder = new Redsys3dSecureBuilder();

        $this->paymentBridge->build3dSecureParameters($secureBuilder);

        $parameters['DS_MERCHANT_EMV3DS'] = $secureBuilder->get();
    }
}
