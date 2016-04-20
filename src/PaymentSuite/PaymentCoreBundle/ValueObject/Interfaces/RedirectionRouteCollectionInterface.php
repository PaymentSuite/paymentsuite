<?php

namespace PaymentSuite\PaymentCoreBundle\ValueObject\Interfaces;

use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;

/**
 * Class RedirectionRouteCollection.
 */
interface RedirectionRouteCollectionInterface
{
    /**
     * Add redirection route.
     *
     * @param RedirectionRoute $redirectionRoute     Redirection Route
     * @param string           $redirectionRouteName Redirection Route name
     *
     * @return $this Self object
     */
    public function addRedirectionRoute(RedirectionRoute $redirectionRoute, $redirectionRouteName);

    /**
     * Get specific redirection route.
     *
     * @param string $redirectionRouteName Redirection Route name
     *
     * @return RedirectionRoute Redirect Route
     */
    public function getRedirectionRoute($redirectionRouteName);
}
