<?php

namespace PaymentSuite\PaylandsBundle\ApiClient;

/**
 * Interface ClientInterface.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
interface ApiClientInterface
{
    const OPERATIVE_AUTHORIZATION = 'AUTHORIZATION';
    const OPERATIVE_DEFERRED = 'DEFERRED';

    /**
     * Gets if client is in sandbox mode.
     *
     * @return bool
     */
    public function isModeSandboxEnabled();

    /**
     * Gets current client's service.
     *
     * @return string
     */
    public function getCurrentService();

    /**
     * Gets current client's operative.
     *
     * @return string
     */
    public function getOperative();

    /**
     * Gets current template uuid to use to capture card.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Requests Paylands API to create a new customer.
     *
     * @param int $customerExtId Customer external id to map to application
     *
     * @return array
     */
    public function createCustomer($customerExtId);

    /**
     * Requests Paylands API to retrieve tokenized cards of a customer.
     *
     * @param int $customerExtId Customer external id
     *
     * @return array
     */
    public function retrieveCustomerCards($customerExtId);

    /**
     * Requests Paylands API to create a new payment order.
     *
     * @param string $customerExtId
     * @param int    $amount
     * @param string $description
     *
     * @return array
     */
    public function createPayment($customerExtId, $amount, $description);

    /**
     * Requests Paylands API to pay a previously created order.
     *
     * @param string $ip
     * @param string $orderUuid
     * @param string $cardUuid
     *
     * @return array
     */
    public function directPayment($ip, $orderUuid, $cardUuid);

    /**
     * Requests Paylands API to refund (totally or partially) a previously paid order.
     *
     * @param string $orderUuid
     * @param int    $amount
     *
     * @return array
     */
    public function refundPayment($orderUuid, $amount = null);

    /**
     * Requests Paylands API to confirm a previously created 'deferred' order.
     *
     * @param string $orderUuid
     *
     * @return array
     */
    public function confirmPayment($orderUuid);

    /**
     * Requests Paylands API to cancel a previously created 'deferred' order.
     *
     * @param string $orderUuid
     *
     * @return array
     */
    public function cancelPayment($orderUuid);
}
