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

namespace PaymentSuite\PaypalWebCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class PaypalWebCheckoutMethod.
 *
 * Mirrors Paypal IPN message issued when confirming a payment.
 * This class is used to wrap the message in a class
 *
 * @link   https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/#id091EB04C0HS
 */
class PaypalWebCheckoutMethod implements PaymentMethodInterface
{
    /**
     * @var string
     *
     * The status of the payment. Possible values are:
     *
     * Canceled_Reversal:
     *      A reversal has been canceled. For example, you won a dispute with the customer,
     *      and the funds for the transaction that was reversed have been returned to you.
     *
     * Completed:
     *      The payment has been completed, and the funds have been added successfully to your account balance.
     *
     * Created:
     *      A German ELV payment is made using Express Checkout.
     *
     * Denied:
     *      The payment was denied. This happens only if the payment was previously pending because
     *      of one of the reasons listed for the pending_reason variable or the Fraud_Management_Filters_x variable.
     *
     * Expired:
     *      This authorization has expired and cannot be captured.
     *
     * Failed:
     *      The payment has failed. This happens only if the payment was made from your customer's bank account.
     *
     * Pending:
     *      The payment is pending. See pending_reason for more information.
     *
     * Refunded:
     *      You refunded the payment.
     *
     * Reversed:
     *      A payment was reversed due to a chargeback or other type of reversal. The funds have been
     *      removed from your account balance and returned to the buyer. The reason for the reversal
     *      is specified in the ReasonCode element.
     * Processed:
     *      A payment has been accepted.
     *
     * Voided:
     *      This authorization has been voided.
     */
    private $paymentStatus;

    /**
     * @var string
     *
     * Message's version number
     */
    private $notifyVersion;

    /**
     * @var string
     *
     * Whether the customer has a verified PayPal account:
     *
     * verified:
     *      Customer has a verified PayPal account.
     *
     * unverified:
     *      Customer has an unverified PayPal account.
     */
    private $payerStatus;

    /**
     * @var string
     *
     * Email address or account ID of the payment recipient (that is, the merchant).
     * Equivalent to the values of receiver_email (if payment is sent to primary account)
     * and business set in the Website Payment HTML.
     *
     * Note: The value of this variable is normalized to lowercase characters.
     * Length: 127 characters
     */
    private $business;

    /**
     * @var int
     *
     * Quantity as entered by your customer or as passed by you, the merchant.
     * If this is a shopping cart transaction, PayPal appends the number of the item (e.g. quantity1, quantity2).
     */
    private $quantity;

    /**
     * @var string
     *
     * Payment type. Possible values:
     *
     * echeck:
     *      This payment was funded with an eCheck.
     *
     * instant:
     *      This payment was funded with PayPal balance, credit card, or Instant Transfer.
     */
    private $paymentType;

    /**
     * @var string
     *
     * Primary email address of the payment recipient (that is, the merchant).
     * If the payment is sent to a non-primary email address on your PayPal account,
     * the receiver_email is still your primary email.
     *
     * Note: The value of this variable is normalized to lowercase characters.
     * Length: 127 characters
     */
    private $receiverEmail;

    /**
     * @var string
     *
     * This variable is set only if payment_status is Pending.
     *
     * address:
     *      The payment is pending because your customer did not include a confirmed shipping address
     *      and your Payment Receiving Preferences is set yo allow you to manually accept or deny
     *      each of these payments. To change your preference, go to the Preferences section of your Profile.
     *
     * authorization:
     *      You set the payment action to Authorization and have not yet captured funds.
     *
     * echeck:
     *      The payment is pending because it was made by an eCheck that has not yet cleared.
     *
     * intl:
     *      The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism.
     *      You must manually accept or deny this payment from your Account Overview.
     *
     * multi-currency:
     *      You do not have a balance in the currency sent, and you do not have your profiles's
     *      Payment Receiving Preferences option set to automatically convert and accept this payment.
     *      As a result, you must manually accept or deny this payment.
     *
     * order:
     *      You set the payment action to Order and have not yet captured funds.
     *
     * paymentreview:
     *      The payment is pending while it is reviewed by PayPal for risk.
     *
     * regulatory_review:
     *      The payment is pending because PayPal is reviewing it for compliance with
     *      government regulations. PayPal will complete this review within 72 hours.
     *      When the review is complete, you will receive a second IPN message whose
     *      payment_status/reason code variables indicate the result.
     *
     * unilateral:
     *      The payment is pending because it was made to an email address that is
     *      not yet registered or confirmed.
     *
     * upgrade:
     *      The payment is pending because it was made via credit card and you must upgrade your account
     *      to Business or Premier status before you can receive the funds. upgrade can also mean that
     *      you have reached the monthly limit for transactions on your account.
     *
     * verify:
     *      The payment is pending because you are not yet verified. You must verify your account before
     *      you can accept this payment.
     *
     * other:
     *      The payment is pending for a reason other than those listed above.
     *      For more information, contact PayPal Customer Service.
     */
    private $pendingReason;

    /**
     * @var string
     *
     * IPN Transaction Type
     *
     * Typically, your back-end or administrative processes will perform specific actions based on the kind
     * of IPN message received. You can use the txn_type variable in the message to trigger the kind of
     * processing you want to perform.
     *
     * Possible statuses:
     *
     * adjustment:
     *      A dispute has been resolved and closed
     *
     * cart:
     *      Payment received for multiple items; source is Express Checkout or the PayPal Shopping Cart.
     *
     * express_checkout:
     *      Payment received for a single item; source is Express Checkout
     *
     * masspay:
     *      Payment sent using Mass Pay
     *
     * merch_pmt:
     *      Monthly subscription paid for Website Payments Pro
     *
     * mp_cancel:
     *      Billing agreement cancelled
     *
     * mp_signup:
     *      Created a billing agreement
     *
     * new_case:
     *      A new dispute was filed
     *
     * payout:
     *      A payout related to a global shipping transaction was completed.
     *
     * pro_hosted:
     *      Payment received; source is Website Payments Pro Hosted Solution.
     *
     * recurring_payment:
     *      Recurring payment received
     *
     * recurring_payment_expired:
     *      Recurring payment expired
     *
     * recurring_payment_failed:
     *      Recurring payment failed. This transaction type is sent if:
     *      * The attempt to collect a recurring payment fails
     *      * The "max failed payments" setting in the customer's recurring payment profile is 0
     *      * In this case, PayPal tries to collect the recurring payment an unlimited number of
     *        times without ever suspending the customer's recurring payments profile.
     *
     * recurring_payment_profile_cancel:
     *      Recurring payment profile canceled
     *
     * recurring_payment_profile_created:
     *      Recurring payment profile created
     *
     * recurring_payment_skipped:
     *      Recurring payment skipped; it will be retried up to 3 times, 5 days apart
     *
     * recurring_payment_suspended:
     *      Recurring payment suspended. This transaction type is sent if PayPal tried to
     *      collect a recurring payment, but the related recurring payments profile has been suspended.
     *
     * recurring_payment_suspended_due_to_max_failed_payment:
     *      Recurring payment failed and the related recurring payment profile has been suspended.
     *      This transaction type is sent if:
     *      * PayPal's attempt to collect a recurring payment failed
     *      * The "max failed payments" setting in the customer's recurring payment profile is 1 or greater
     *      * the number of attempts to collect payment has exceeded the value specified for "max failed payments"
     *        In this case, PayPal suspends the customer's recurring payment profile.
     *
     * send_money:
     *      Payment received; source is the Send Money tab on the PayPal website
     *
     * subscr_cancel:
     *      Subscription canceled
     *
     * subscr_eot:
     *      Subscription expired
     *
     * subscr_failed:
     *      Subscription payment failed
     *
     * subscr_modify:
     *      Subscription modified
     *
     * subscr_payment:
     *      Subscription payment received
     *
     * subscr_signup:
     *      Subscription started
     *
     * virtual_terminal:
     *      Payment received; source is Virtual Terminal
     *
     * web_accept:
     *      Payment received; source is any of the following:
     *      * A Direct Credit Card (Pro) transaction
     *      * A Buy Now, Donation or Smart Logo for eBay auctions button
     */
    private $txnType;

    /**
     * @var string
     *
     * Item name as passed by you, the merchant. Or, if not passed by you, as entered by your customer.
     * If this is a shopping cart transaction, PayPal will append the number of the item
     * (e.g., item_name1, item_name2, and so forth).
     *
     * Length: 127 characters
     */
    private $itemName;

    /**
     * @var string
     *
     * For payment IPN notifications, this is the currency of the payment.
     *
     * For non-payment subscription IPN notifications (i.e., txn_type= signup, cancel, failed, eot, or modify),
     * this is the currency of the subscription.
     *
     * For payment subscription IPN notifications, it is the currency of the payment
     * (i.e., txn_type = subscr_payment)
     */
    private $mcCurrency;

    /**
     * @var int
     *
     * Whether the message is a test message. It is one of the following values:
     *
     * 1:
     *      the message is directed to the Sandbox
     */
    private $testIpn;

    /**
     * @var float
     *
     * Full amount of the customer's payment, before transaction fee is subtracted.
     *
     * Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a
     * refund or reversal, and either of those payment statuses can be for the full or partial
     * amount of the original transaction.
     */
    private $mcGross;

    /**
     * @var string
     *
     * Pass-through variable for you to track purchases. It will get passed back to you at the completion
     * of the payment. If omitted, no variable will be passed back to you. If this is a shopping cart transaction,
     * PayPal will append the number of the item (e.g., item_number1, item_number2, and so forth)
     *
     * Length: 127 characters
     */
    private $itemNumber;

    /**
     * @var string
     *
     * Encrypted string used to validate the authenticity of the transaction
     */
    private $verifySign;

    /**
     * @var string
     *
     * Customer's primary email address. Use this email to provide any credits.
     * Length: 127 characters
     */
    private $payerEmail;

    /**
     * @var string
     *
     * The merchant's original transaction identification number for the payment from the buyer,
     * against which the case was registered.
     */
    private $txnId;

    /**
     * @var string
     *
     * Internal; only for use by MTS and DTS
     */
    private $ipnTrackId;

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
     */
    public function __construct(
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
        $this->mcGross = $mcGross;
        $this->paymentStatus = $paymentStatus;
        $this->notifyVersion = $notifyVersion;
        $this->payerStatus = $payerStatus;
        $this->business = $business;
        $this->quantity = $quantity;
        $this->verifySign = $verifySign;
        $this->payerEmail = $payerEmail;
        $this->txnId = $txnId;
        $this->paymentType = $paymentType;
        $this->receiverEmail = $receiverEmail;
        $this->pendingReason = $pendingReason;
        $this->txnType = $txnType;
        $this->itemName = $itemName;
        $this->mcCurrency = $mcCurrency;
        $this->itemNumber = $itemNumber;
        $this->testIpn = $testIpn;
        $this->ipnTrackId = $ipnTrackId;
    }

    /**
     * Return type of payment name.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return 'paypal_web_checkout';
    }

    /**
     * Get PaymentStatus.
     *
     * @return string PaymentStatus
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Get NotifyVersion.
     *
     * @return string NotifyVersion
     */
    public function getNotifyVersion()
    {
        return $this->notifyVersion;
    }

    /**
     * Get PayerStatus.
     *
     * @return string PayerStatus
     */
    public function getPayerStatus()
    {
        return $this->payerStatus;
    }

    /**
     * Get Business.
     *
     * @return string Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Get Quantity.
     *
     * @return int Quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get PaymentType.
     *
     * @return string PaymentType
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Get ReceiverEmail.
     *
     * @return string ReceiverEmail
     */
    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    /**
     * Get PendingReason.
     *
     * @return string PendingReason
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * Get TxnType.
     *
     * @return string TxnType
     */
    public function getTxnType()
    {
        return $this->txnType;
    }

    /**
     * Get ItemName.
     *
     * @return string ItemName
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * Get McCurrency.
     *
     * @return string McCurrency
     */
    public function getMcCurrency()
    {
        return $this->mcCurrency;
    }

    /**
     * Get TestIpn.
     *
     * @return int TestIpn
     */
    public function getTestIpn()
    {
        return $this->testIpn;
    }

    /**
     * Get McGross.
     *
     * @return float McGross
     */
    public function getMcGross()
    {
        return $this->mcGross;
    }

    /**
     * Get ItemNumber.
     *
     * @return string ItemNumber
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    /**
     * Get VerifySign.
     *
     * @return string VerifySign
     */
    public function getVerifySign()
    {
        return $this->verifySign;
    }

    /**
     * Get PayerEmail.
     *
     * @return string PayerEmail
     */
    public function getPayerEmail()
    {
        return $this->payerEmail;
    }

    /**
     * Get TxnId.
     *
     * @return string TxnId
     */
    public function getTxnId()
    {
        return $this->txnId;
    }

    /**
     * Get IpnTrackId.
     *
     * @return string IpnTrackId
     */
    public function getIpnTrackId()
    {
        return $this->ipnTrackId;
    }
}
