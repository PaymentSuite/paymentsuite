<?php

/**
 * SafetypayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 *
 */

namespace PaymentSuite\SafetyPayBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * SafetyPay router
 */
class SafetypayRoutesLoader implements LoaderInterface
{
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
     * Execution controller route confirm
     */
    private $controllerRouteConfirm;

    /**
     * @var string
     *
     * Execution controller route  confirm name
     */
    private $controllerRouteConfirmName;

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
     * @param String $controllerRouteConfirm     Controller route confirm
     * @param String $controllerRouteConfirmName Controller route name confirm
     */
    public function __construct($controllerRoute, $controllerRouteName, $controllerRouteConfirm, $controllerRouteConfirmName)
    {
        $this->controllerRoute = $controllerRoute;
        $this->controllerRouteName = $controllerRouteName;
        $this->controllerRouteConfirm = $controllerRouteConfirm;
        $this->controllerRouteConfirmName = $controllerRouteConfirmName;
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @throws \RuntimeException Loader is added twice
     * @return RouteCollection
     *
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {

            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();

        $routes->add($this->controllerRouteName, new Route($this->controllerRoute, array(
                '_controller'   =>  'SafetypayBundle:Safetypay:execute',
        )));

        $routes->add($this->controllerRouteConfirmName, new Route($this->controllerRouteConfirm, array(
            '_controller'   =>  'SafetypayBundle:Safetypay:confirm',
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
        return 'safetypay' === $type;
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
