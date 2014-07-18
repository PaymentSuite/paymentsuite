<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Visanet router
 */
class VisanetRoutesLoader implements LoaderInterface
{
    /**
     * @var string
     *
     * Execution route name
     */
    protected $executeRouteName;

    /**
     * @var string
     *
     * Execution route schemes
     */
    protected $executeRouteSchemes;

    /**
     * @var string
     *
     * Execution controller route
     */
    protected $executeRoute;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    protected $loaded = false;

    /**
     * Construct method
     *
     * @param string $executeRouteName    Execute route name
     * @param string $executeRouteSchemes Execute route schemes
     * @param string $executeRoute        Execute route
     */
    public function __construct($executeRouteName, $executeRouteSchemes, $executeRoute)
    {
        $this->executeRouteName = $executeRouteName;
        $this->executeRouteSchemes = $executeRouteSchemes;
        $this->executeRoute = $executeRoute;
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
            $this->executeRouteName,
            new Route(
                $this->executeRoute,
                array('_controller' => 'PayuBundle:Visanet:execute'),
                array(),
                array(),
                '',
                $this->executeRouteSchemes
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
        return 'visanet' === $type;
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
