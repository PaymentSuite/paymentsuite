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

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\CurrencyNotSupportedException;

/**
 * Class PaypalFormTypeWrapper
 */
class PaypalWebCheckoutFormTypeFactory
{
    /**
     * @var PaypalWebCheckoutUrlFactory
     *
     * Url factory
     */
    private $urlFactory;

    /**
     * @var FormFactory
     *
     * Form factory
     */
    private $formFactory;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var string
     *
     * Merchant identifier
     */
    private $business;

    /**
     * Formtype construct method
     *
     * @param PaypalWebCheckoutUrlFactory $urlFactory    URL Factory service
     * @param PaymentBridgeInterface      $paymentBridge Payment bridge
     * @param FormFactory                 $formFactory   Form factory
     * @param string                      $business      merchant code
     */
    public function __construct(
        PaypalWebCheckoutUrlFactory $urlFactory,
        PaymentBridgeInterface $paymentBridge,
        FormFactory $formFactory,
        $business
    ) {
        $this->urlFactory = $urlFactory;
        $this->paymentBridge = $paymentBridge;
        $this->formFactory = $formFactory;
        $this->business = $business;
    }

    /**
     * Builds form given return, success and fail urls
     *
     * @return FormView
     */
    public function buildForm()
    {
        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $orderId = $this
            ->paymentBridge
            ->getOrderId();

        $orderCurrency = $this
            ->paymentBridge
            ->getCurrency();

        $this->checkCurrency($orderCurrency);

        /**
         * Creates the success return route, when coming back
         * from PayPal web checkout
         */
        $successReturnUrl = $this
            ->urlFactory
            ->getSuccessReturnUrlForOrderId($orderId);

        /**
         * Creates the cancel payment route, when cancelling
         * the payment process from PayPal web checkout
         */
        $cancelReturnUrl = $this
            ->urlFactory
            ->getCancelReturnUrlForOrderId($orderId);

        /**
         * Creates the IPN payment notification route,
         * which is triggered after PayPal processes the
         * payment and returns the validity of the transaction
         *
         * For forther information
         *
         * https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/
         * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNIntro/
         */
        $processUrl = $this
            ->urlFactory
            ->getProcessUrlForOrderId($orderId);

        $formBuilder
            ->setAction($this->urlFactory->getApiEndpoint())
            ->setMethod('POST')
            ->add('business', 'hidden', [
                'data' => $this->business,
            ])
            ->add('return', 'hidden', [
                'data' => $successReturnUrl,
            ])
            ->add('cancel_return', 'hidden', [
                'data' => $cancelReturnUrl,
            ])
            ->add('notify_url', 'hidden', [
                'data' => $processUrl,
            ])
            ->add('currency_code', 'hidden', [
                'data' => $orderCurrency,
            ])
            ->add('env', 'hidden', [
                'data' => '',
            ]);

        /**
         * Create a PayPal cart line for each order line.
         *
         * Project specific PaymentBridgeInterface::getExtraData
         * should return an array of this form
         *
         *   ['items' => [
         *       0 => [ 'item_name' => 'Item 1', 'amount' => 1234, 'quantity' => 2 ],
         *       1 => [ 'item_name' => 'Item 2', 'amount' => 2345, 'quantity' => 1 ],
         *   ]]
         *
         * The 'items' key consists of an array with the basic information
         * of each line of the order. Amount is the price of the product,
         * not the total of the order line
         *
         */
        $cartData = $this->paymentBridge->getExtraData();
        $itemsData = $cartData['items'];
        $iteration = 1;

        foreach ($itemsData as $orderLine) {
            $formBuilder
                ->add('item_name_' . $iteration, 'hidden', [
                    'data' => $orderLine['item_name'],
                ])
                ->add('amount_' . $iteration, 'hidden', [
                    'data' => $orderLine['amount'] / 100,
                ])
                ->add('quantity_' . $iteration, 'hidden', [
                    'data' => $orderLine['quantity'],
                ]);
            $iteration++;
        }

        if (isset($cartData['discount_amount_cart'])) {
            $formBuilder->add('discount_amount_cart', 'hidden', [
                'data' => $cartData['discount_amount_cart'] / 100,
            ]);
        }

        return $formBuilder
            ->getForm()
            ->createView();
    }

    /**
     * Check currency
     *
     * @param string $currency Currency
     *
     * @throws CurrencyNotSupportedException Currency not supported
     */
    public function checkCurrency($currency)
    {
        $allowedCurrencies = [
            'AUD',
            'BRL',
            'CAD',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'ILS',
            'JPY',
            'MYR',
            'MXN',
            'NOK',
            'NZD',
            'PHP',
            'PLN',
            'GBP',
            'RUB',
            'SGD',
            'SEK',
            'CHF',
            'TWD',
            'THB',
            'TRY',
            'USD',
        ];

        if (!in_array($currency, $allowedCurrencies)) {
            throw new CurrencyNotSupportedException();
        }
    }
}
