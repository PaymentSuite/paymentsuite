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

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\ParameterNotReceivedException;

/**
 * Class PaypalWebCheckoutManager.
 */
class PaypalWebCheckoutManager
{
    /**
     * @var PaypalWebCheckoutUrlFactory
     *
     * URL factory
     */
    private $urlFactory;

    /**
     * @var PaypalWebCheckoutFormTypeFactory
     *
     * FormType factory
     */
    private $formTypeFactory;

    /**
     * @var PaypalWebCheckoutMethodFactory
     *
     * Payment method factory
     */
    private $paymentMethodFactory;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    private $paymentEventDispatcher;

    /**
     * Construct method for paypal manager.
     *
     * @param PaypalWebCheckoutUrlFactory      $urlFactory             Url factory
     * @param PaypalWebCheckoutFormTypeFactory $formTypeFactory        FormType factory
     * @param PaypalWebCheckoutMethodFactory   $paymentMethodFactory   Payment method factory
     * @param PaymentBridgeInterface           $paymentBridge          Payment Bridge
     * @param PaymentEventDispatcher           $paymentEventDispatcher Event dispatcher
     */
    public function __construct(
        PaypalWebCheckoutUrlFactory $urlFactory,
        PaypalWebCheckoutFormTypeFactory $formTypeFactory,
        PaypalWebCheckoutMethodFactory $paymentMethodFactory,
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher
    ) {
        $this->urlFactory = $urlFactory;
        $this->formTypeFactory = $formTypeFactory;
        $this->paymentMethodFactory = $paymentMethodFactory;
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
    }

    /**
     * Dispatches order load event and prepares paypal form for submission.
     *
     * This is a synchronous action that takes place on the implementor
     * side, i.e. right after click the "pay with checkout" button it the
     * final stage of a checkout process.
     *
     * See documentation for PaypalWebCheckout Api Integration at
     *
     * @link https://developer.paypal.com/docs/integration/web/web-checkout/
     *
     * @throws PaymentOrderNotFoundException
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function generatePaypalForm()
    {
        $paypalMethod = $this
            ->paymentMethodFactory
            ->createEmpty();

        /**
         * We expect listeners for the payment.order.load event
         * to store the Order into the bridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $paypalMethod
            );

        /**
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * We expect the Order to be created and physically flushed.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paypalMethod
            );

        return $this
            ->formTypeFactory
            ->buildForm();
    }

    /**
     * Process Paypal IPN response to payment.
     *
     * When the IPN mesage is validated, a payment success event
     * should be dispatched.
     *
     * @param int   $orderId    Order Id
     * @param array $parameters parameter array coming from Paypal IPN notification
     *
     * @throws ParameterNotReceivedException
     * @throws PaymentException
     */
    public function processPaypalIPNMessage($orderId, array $parameters)
    {
        /**
         * Retrieving the order object.
         */
        $order = $this
            ->paymentBridge
            ->findOrder($orderId);

        if (!$order) {
            throw new PaymentOrderNotFoundException(sprintf(
                    'Order #%s not found', $orderId)
            );
        }

        $this
            ->paymentBridge
            ->setOrder($order);

        /**
         * Check that we receive the mandatory parameters.
         */
        $this->checkResultParameters($parameters);

        /**
         * Initializing PaypalWebCheckoutMethod, which is
         * an object representation of the payment information
         * coming from the payment processor.
         */
        $paypalMethod = $this
            ->paymentMethodFactory
            ->create(
                $parameters['mc_gross'],
                $parameters['payment_status'],
                $parameters['notify_version'],
                $parameters['payer_status'],
                $parameters['business'],
                null,
                $parameters['verify_sign'],
                $parameters['payer_email'],
                $parameters['txn_id'],
                $parameters['payment_type'],
                $parameters['receiver_email'],
                null,
                $parameters['txn_type'],
                null,
                $parameters['mc_currency'],
                null,
                $parameters['test_ipn'],
                $parameters['ipn_track_id']
            );

        /**
         * Notifying payment.done, which means that the
         * payment has been received, although we still
         * do not know if it is succesful or not.
         * Listening fot this event is useful when one
         * wants to record transaction informations.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $paypalMethod
            );

        /**
         * Check if the transaction is successful.
         */
        if (!$this->transactionSuccessful($parameters)) {
            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $paypalMethod
                );

            throw new PaymentException();
        }

        /**
         * Payment paid successfully.
         *
         * Paid process has ended successfully
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $paypalMethod
            );
    }

    /**
     * Checks that all the required parameters are received.
     *
     * @param array $parameters Parameters
     *
     * @throws ParameterNotReceivedException
     */
    private function checkResultParameters(array $parameters)
    {
        $requiredParameters = [
            'payment_status',
        ];

        foreach ($requiredParameters as $requiredParameter) {
            if (!isset($parameters[$requiredParameter])) {
                throw new ParameterNotReceivedException($requiredParameter);
            }
        }
    }

    /**
     * Check if transaction is complete.
     *
     * When we receive an IPN response, we should
     * check that the price paid corresponds to the
     * amount stored in the PaymentMethod. This double
     * check is essential since the web checkout form
     * could be mangled.
     *
     * @link https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNIntro/
     *
     * @param array $ipnParameters Paypal IPN parameters
     *
     * @return bool
     */
    private function transactionSuccessful($ipnParameters)
    {
        /**
         * First of all we have to check the validity of the IPN
         * message. We need to send back the contents of the query
         * string coming from Paypal's IPN message.
         */
        $ipnNotifyValidateUrl = $this->urlFactory->getApiEndpoint()
            . '?'
            . http_build_query(
                array_merge(
                    $this->urlFactory->getPaypalNotifyValidateQueryParam(),
                    $ipnParameters)
            );
        $ipnValidated = (file_get_contents($ipnNotifyValidateUrl) == 'VERIFIED');

        /**
         * Matching paid amount with the originating order amount,
         * this is a security check to prevent frauds by manually
         * changing the papal form.
         */
        $amountMatches = $this->paymentBridge->getAmount() / 100 == $ipnParameters['mc_gross'];
        $amountMatches = $amountMatches && $this->paymentBridge->getCurrency() == ($ipnParameters['mc_currency']);

        /**
         * When a transaction is successful, payment_status has a 'Completed' value.
         */

        return $amountMatches && $ipnValidated && (strcmp($ipnParameters['payment_status'], 'Completed') === 0);
    }
}
