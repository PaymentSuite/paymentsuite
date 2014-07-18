<?php

/**
 * DineromailBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package DineromailBundle
 *
 * Marc Morera 2013
 */

namespace PaymentSuite\DineroMailBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Dineromail router
 */
class DineromailRoutesLoader implements LoaderInterface
{

    /**
     * @var string
     *
     * Execution route name
     */
    const ROUTE_NAME = 'dineromail_execute';

    /**
     * @var string
     *
     * Process url. For callbacks
     */
    const ROUTE_PROCESS_NAME = 'dineromail_process';

    /**
     * @var string
     *
     * Execution controller route
     */
    private $controllerRoute;

    /**
     * @var string
     *
     * Execution controller route name
     */
    private $controllerRouteName;

    /**
     * @var string
     *
     * Process controller route
     */
    private $controllerProcessRoute;

    /**
     * @var string
     *
     * Process controller route name
     */
    private $controllerProcessRouteName;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

    /**
     * Construct method
     *
     * @param string $controllerRoute            Controller route
     * @param string $controllerRouteName        Controller route name
     * @param string $controllerProcessRoute     Process controller route
     * @param string $controllerProcessRouteName Process controller route name
     */
    public function __construct($controllerRoute, $controllerRouteName, $controllerProcessRoute, $controllerProcessRouteName)
    {
        $this->controllerRoute = $controllerRoute;
        $this->controllerRouteName = $controllerRouteName;
        $this->controllerProcessRoute = $controllerProcessRoute;
        $this->controllerProcessRouteName = $controllerProcessRouteName;
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @return RouteCollection
     *
     * @throws RuntimeException Loader is added twice
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {

            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();

        $routes->add($this->controllerRouteName, new Route($this->controllerRoute, array(
                '_controller'   =>  'DineromailBundle:Dineromail:execute',
        )));

        $routes->add($this->controllerProcessRouteName, new Route($this->controllerProcessRoute, array(
                '_controller'   =>  'DineromailBundle:Dineromail:process',
        )));

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
        return 'dineromail' === $type;
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
