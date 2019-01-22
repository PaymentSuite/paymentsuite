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

namespace PaymentSuite\GestpayBundle\Services;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * Class GestpayTransactionIdAssembler.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayTransactionIdAssembler
{
    const SEPARTOR = 'T';
    /**
     * @var PaymentBridgeInterface
     */
    protected $paymentBridge;

    /**
     * GestpayTransactionIdAssembler constructor.
     *
     * @param PaymentBridgeInterface $paymentBridge
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }

    /**
     * Returns gestpay shop transaction id.
     *
     * @return string
     */
    public function assemble()
    {
        $orderId = $this->paymentBridge->getOrderId();

        return sprintf('%d%s%d', $orderId, self::SEPARTOR, time());
    }

    /**
     * Extracts order id from shop transaction id.
     *
     * @param string $shopTransactionId
     *
     * @return int
     */
    public function extract(string $shopTransactionId)
    {
        $pieces = explode(self::SEPARTOR, $shopTransactionId);

        return (int) $pieces[0];
    }
}
