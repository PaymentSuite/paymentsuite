<?php

/**
 * SafetypayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package SafetypayBundle
 *
 */

namespace Scastells\SafetypayBundle\Router;

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
     * Execution controller route success
     */
    private $controllerRouteSuccess;


    /**
     * @var string
     *
     * Execution controller route  success name
     */
    private $controllerRouteSuccessName;


    /**
     * @var string
     *
     * Execution controller route fail
     */
    private $controllerRouteFail;


    /**
     * @var string
     *
     * Execution controller route fail name
     */
    private $controllerRouteFailName;


    /**
     * @var boolean
     * 
     * Route is loaded
     */
    private $loaded = false;


    /**
     * Construct method
     *
     * @param string $controllerRoute Controller route
     * @param string $controllerRouteName Controller route name
     * @param $controllerRouteSuccess Controller route
     * @param $controllerRouteSuccessName Controller route name
     * @param $controllerRouteFail
     * @param $controllerRouteFailName
     */
    public function __construct($controllerRoute, $controllerRouteName, $controllerRouteSuccess, $controllerRouteSuccessName, $controllerRouteFail, $controllerRouteFailName)
    {
        $this->controllerRoute = $controllerRoute;
        $this->controllerRouteName = $controllerRouteName;
        $this->controllerRouteSuccess = $controllerRouteSuccess;
        $this->controllerRouteSuccessName = $controllerRouteSuccessName;
        $this->controllerRouteFail = $controllerRouteFail;
        $this->controllerRouteFailName = $controllerRouteFailName;
    }


    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type
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


        $routes->add($this->controllerRouteSuccessName, new Route($this->controllerRouteSuccess, array(
            '_controller'   =>  'SafetypayBundle:Safetypay:success',
        )));

        $routes->add($this->controllerRouteFailName, new Route($this->controllerRouteFail, array(
            '_controller'   =>  'SafetypayBundle:Safetypay:fail',
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