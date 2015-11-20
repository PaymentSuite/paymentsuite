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

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;

/**
 * Class PaypalWebCheckoutUrlFactory
 */
class PaypalWebCheckoutUrlFactory
{
    /**
     * @var RedirectionRouteCollection
     *
     * Redirection Route collection
     */
    private $redirectRoutes;

    /**
     * @var UrlGeneratorInterface
     *
     * Url generator
     */
    private $urlGenerator;

    /**
     * @var string
     *
     * Paypal Base Paypal cgi-bin URL
     */
    private $apiEndpoint;

    /**
     * Construct
     *
     * @param RedirectionRouteCollection $redirectRoutes Redirection Route collection
     * @param UrlGeneratorInterface      $urlGenerator   Url generator
     * @param string                     $apiEndpoint    Paypal Base Paypal cgi-bin URL
     */
    public function __construct(
        RedirectionRouteCollection $redirectRoutes,
        UrlGeneratorInterface $urlGenerator,
        $apiEndpoint
    ) {
        $this->redirectRoutes = $redirectRoutes;
        $this->urlGenerator = $urlGenerator;
        $this->apiEndpoint = $apiEndpoint;
    }

    /**
     * Creates the IPN payment notification route,
     * which is triggered after PayPal processes the
     * payment and returns the validity of the transaction
     *
     * For further information
     *
     * @link https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     * @link https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNIntro/
     */
    public function getProcessUrlForOrderId($orderId)
    {
        return $this
            ->urlGenerator
            ->generate(
                'paymentsuite_paypal_web_checkout_process',
                ['order_id' => $orderId],
                true
            );
    }

    /**
     * Generate success return url
     *
     * @param string $orderId Order id
     *
     * @return string
     */
    public function getSuccessReturnUrlForOrderId($orderId)
    {
        $redirectRoute = $this
            ->redirectRoutes
            ->getRedirectionRoute('success');

        return $this
            ->urlGenerator
            ->generate(
                $redirectRoute->getRoute(),
                $redirectRoute->getRouteAttributes(
                    $orderId
                )
            );
    }

    /**
     * Generate cancel return url
     *
     * @param string $orderId Order id
     *
     * @return string
     */
    public function getCancelReturnUrlForOrderId($orderId)
    {
        $redirectRoute = $this
            ->redirectRoutes
            ->getRedirectionRoute('cancel');

        return $this
            ->urlGenerator
            ->generate(
                $redirectRoute->getRoute(),
                $redirectRoute->getRouteAttributes(
                    $orderId
                )
            );
    }

    /**
     * Returns the param/value query string for triggering the
     * validation of the paypal IPN message.
     *
     * @link https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNIntro/
     *
     * @return array
     */
    public function getPaypalNotifyValidateQueryParam()
    {
        return ['cmd' => '_notify-validate'];
    }

    /**
     * Get ApiEndpoint
     *
     * @return string ApiEndpoint
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }
}
