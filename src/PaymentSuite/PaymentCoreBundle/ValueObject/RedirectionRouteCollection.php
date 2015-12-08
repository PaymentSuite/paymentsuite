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

namespace PaymentSuite\PaymentCoreBundle\ValueObject;

/**
 * Class RedirectionRouteCollection.
 */
final class RedirectionRouteCollection
{
    /**
     * @var array
     *
     * Redirection Routes
     */
    private $redirectionRoutes = [];

    /**
     * Add redirection route.
     *
     * @param RedirectionRoute $redirectionRoute     Redirection Route
     * @param string           $redirectionRouteName Redirection Route name
     *
     * @return $this Self object
     */
    public function addRedirectionRoute(
        RedirectionRoute $redirectionRoute,
        $redirectionRouteName
    ) {
        $this->redirectionRoutes[$redirectionRouteName] = $redirectionRoute;

        return $this;
    }

    /**
     * Get specific redirection route.
     *
     * @param string $redirectionRouteName Redirection Route name
     *
     * @return RedirectionRoute Redirect Route
     */
    public function getRedirectionRoute($redirectionRouteName)
    {
        return $this->redirectionRoutes[$redirectionRouteName];
    }
}
