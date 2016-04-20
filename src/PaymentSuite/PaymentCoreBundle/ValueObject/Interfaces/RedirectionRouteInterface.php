<?php

namespace PaymentSuite\PaymentCoreBundle\ValueObject\Interfaces;

/**
 * Class RedirectionRoute.
 */
interface RedirectionRouteInterface
{
    /**
     * Get route.
     *
     * @return string Route
     */
    public function getRoute();

    /**
     * Get route attributes.
     *
     * @param mixed $appendValue Value for appending
     *
     * @return array Route attributes
     */
    public function getRouteAttributes($appendValue = null);
}
