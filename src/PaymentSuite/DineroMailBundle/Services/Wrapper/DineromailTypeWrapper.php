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

namespace PaymentSuite\DineroMailBundle\Services\Wrapper;

use Symfony\Component\Form\FormFactory;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;

/**
 * Dineromail manager
 */
class DineromailTypeWrapper
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

    /**
     * @var PaymentBridge
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var string
     *
     * Api endpoint
     */
    private $endPoint;

    /**
     * @var string
     *
     * Merchant
     */
    private $merchant;

    /**
     * @var string
     *
     * Seller name
     */
    private $sellerName;

    /**
     * @var string
     *
     * url of header image ( full url )
     */
    private $headerImage;

    /**
     * @var boolean
     *
     * Redirect to url after payment from platform
     */
    private $urlRedirectEnabled;

    /**
     * @var array
     *
     * Methods available, imploded by comma
     */
    private $paymentMethodsAvailable;

    /**
     * @var integer
     *
     * Code of country.
     * * 1 - Argentina
     * * 2 - Brasil
     * * 3 - Chile
     * * 4 - México
     */
    private $country;

    /**
     * Formtype construct method
     *
     * @param FormFactory            $formFactory             Form factory
     * @param PaymentBridgeInterface $paymentBridge           Payment bridge
     * @param string                 $endPoint                Api end point
     * @param string                 $merchant                Merchant code
     * @param string                 $sellerName              Seller name
     * @param string                 $headerImage             Header image
     * @param string                 $urlRedirectEnabled      Redirect after payment to desired and configured url
     * @param array                  $paymentMethodsAvailable Methods available for platform
     * @param integer                $country                 Country code
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridge, $endPoint, $merchant, $sellerName, $headerImage, $urlRedirectEnabled, $paymentMethodsAvailable, $country)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridge = $paymentBridge;
        $this->endPoint = $endPoint;
        $this->merchant = $merchant;
        $this->sellerName = $sellerName;
        $this->headerImage = $headerImage;
        $this->urlRedirectEnabled = $urlRedirectEnabled;
        $this->paymentMethodsAvailable = $paymentMethodsAvailable;
        $this->country = $country;
    }

    /**
     * Builds form given success and fail urls
     *
     * @param string $dineromailSuccessUrl    Success route url
     * @param string $dineromailFailUrl       Fail route url
     * @param string $dineromailTransactionId Transaction Id
     *
     * @return Form
     */
    public function buildForm($dineromailSuccessUrl, $dineromailFailUrl, $dineromailTransactionId)
    {
        $extraData = $this->paymentBridge->getExtraData();
        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $formBuilder
            ->setAction($this->endPoint)
            ->setMethod('POST')

            /**
             * Parameters injected by construct
             */
            ->add('merchant', 'hidden', array(
                'data'  =>  $this->merchant,
            ))
            ->add('country_id', 'hidden', array(
                'data'  =>  $this->country,
            ))
            ->add('seller_name', 'hidden', array(
                'data'  =>   $this->sellerName,
            ))
            ->add('payment_method_available', 'hidden', array(
                'data'  =>  implode(';', $this->paymentMethodsAvailable),
            ))
            ->add('url_redirect_enabled', 'hidden', array(
                'data'  =>  intval($this->urlRedirectEnabled),
            ))
            ->add('header_image', 'hidden', array(
                'data'  =>  $this->headerImage,
            ))

            /**
             * Payment bridge data
             */
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->paymentBridge->getAmount(), 2)
            ))
            ->add('transaction_id', 'hidden', array(
                'data'  =>  $dineromailTransactionId
            ))
            ->add('currency', 'hidden', array(
                'data'  =>  $this->paymentBridge->getCurrency(),
            ))

            /**
             * Extra data
             */
            ->add('buyer_name', 'hidden', array(
                'data'  =>  $extraData['customer_firstname'],
            ))
            ->add('buyer_lastname', 'hidden', array(
                'data'  =>  $extraData['customer_lastname'],
            ))
            ->add('buyer_email', 'hidden', array(
                'data'  =>  $extraData['customer_email'],
            ))
            ->add('buyer_phone', 'hidden', array(
                'data'  =>  $extraData['customer_phone'],
            ))
            ->add('language', 'hidden', array(
                'data'  =>  $extraData['language'],
            ))

            /**
             * Options injected in method
             */
            ->add('ok_url', 'hidden', array(
                'data'  =>  $dineromailSuccessUrl,
            ))
            ->add('error_url', 'hidden', array(
                'data'  =>  $dineromailFailUrl,
            ))
            ->add('pending_url', 'hidden', array(
                'data'  =>  $dineromailSuccessUrl,
            ));

        $iteration = 1;

        /**
         * Every item defined in the PaymentBridge is added as a simple field
         */
        foreach ($extraData['dinero_mail_items'] as $key => $dineroMailItem) {

            $formBuilder
                ->add('item_name_' . $iteration, 'hidden', array(
                    'data'  =>  $dineroMailItem['name'],
                ))
                ->add('item_quantity_' . $iteration, 'hidden', array(
                    'data'  =>  $dineroMailItem['quantity'],
                ))

                /**
                 * ammount...
                 */
                ->add('item_ammount_' . $iteration, 'hidden', array(
                    'data'  =>  $dineroMailItem['amount'],
                ))
                ->add('item_currency_' . $iteration, 'hidden', array(
                    'data'  =>  $this->paymentBridge->getCurrency(),
                ));

            $iteration++;
        }

        return $formBuilder;
    }

}
