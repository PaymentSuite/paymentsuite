<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\PayUBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Payu router
 */
class PayuRoutesLoader implements LoaderInterface
{
    /**
     * @var string
     *
     * Notify route name
     */
    protected $notifyRouteName;

    /**
     * @var string
     *
     * Notify route schemes
     */
    protected $notifyRouteSchemes;

    /**
     * @var string
     *
     * Notify controller route
     */
    protected $notifyRoute;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    protected $loaded = false;

    /**
     * Construct method
     *
     * @param string $notifyRouteName    Notify route name
     * @param string $notifyRouteSchemes Notify route schemes
     * @param string $notifyRoute        Notify route
     */
    public function __construct($notifyRouteName, $notifyRouteSchemes, $notifyRoute)
    {
        $this->notifyRouteName = $notifyRouteName;
        $this->notifyRouteSchemes = $notifyRouteSchemes;
        $this->notifyRoute = $notifyRoute;
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @return RouteCollection
     *
     * @throws \RuntimeException Loader is added twice
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {

            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();
        $routes->add(
            $this->notifyRouteName,
            new Route(
                $this->notifyRoute,
                array('_controller' => 'PayuBundle:Payu:notify'),
                array(),
                array(),
                '',
                $this->notifyRouteSchemes
            )
        );
        $this->loaded = true;

        return $routes;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'payu' === $type;
    }

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
    }

    /**
     * Sets the loader resolver.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
