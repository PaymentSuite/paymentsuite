<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaypalWebCheckoutBundle\Services;

use PaymentSuite\PaypalWebCheckoutBundle\PaypalWebCheckoutEmptyMethod;
use PaymentSuite\PaypalWebCheckoutBundle\PaypalWebCheckoutMethod;

/**
 * Class PaypalWebCheckoutMethodFactory.
 */
class PaypalWebCheckoutMethodFactory
{
    /**
     * Create a new empty PaypalWebCheckoutEmptyMethod instance.
     *
     * @return PaypalWebCheckoutEmptyMethod
     */
    public function createEmpty()
    {
        return new PaypalWebCheckoutEmptyMethod();
    }

    /**
     * Initialize Paypal Method using an array which represents
     * the parameters coming from the IPN message as shown in.
     *
     * https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/#id091EAB0105Z
     *
     * @param float  $mcGross       Mc gross
     * @param string $paymentStatus Payment status
     * @param string $notifyVersion Notify version
     * @param string $payerStatus   Payer status
     * @param string $business      Business
     * @param string $quantity      Quantity
     * @param string $verifySign    Verify sign
     * @param string $payerEmail    Payer email
     * @param string $txnId         Txn id
     * @param string $paymentType   Payment type
     * @param string $receiverEmail Reciever email
     * @param string $pendingReason Pending reason
     * @param string $txnType       Txn type
     * @param string $itemName      Item name
     * @param string $mcCurrency    Mc currency
     * @param string $itemNumber    Item number
     * @param string $testIpn       Test ipn
     * @param string $ipnTrackId    Ipn track id
     *
     * @return PaypalWebCheckoutMethod Method instance
     */
    public function create(
        $mcGross = null,
        $paymentStatus = null,
        $notifyVersion = null,
        $payerStatus = null,
        $business = null,
        $quantity = null,
        $verifySign = null,
        $payerEmail = null,
        $txnId = null,
        $paymentType = null,
        $receiverEmail = null,
        $pendingReason = null,
        $txnType = null,
        $itemName = null,
        $mcCurrency = null,
        $itemNumber = null,
        $testIpn = null,
        $ipnTrackId = null
    ) {
        return new PaypalWebCheckoutMethod(
            $mcGross,
            $paymentStatus,
            $notifyVersion,
            $payerStatus,
            $business,
            $quantity,
            $verifySign,
            $payerEmail,
            $txnId,
            $paymentType,
            $receiverEmail,
            $pendingReason,
            $txnType,
            $itemName,
            $mcCurrency,
            $itemNumber,
            $testIpn,
            $ipnTrackId
        );
    }
}
