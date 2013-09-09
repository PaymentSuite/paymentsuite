<?php

/**
 * BeFactory PaymillBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package PaymillBundle
 *
 * Mmoreram 2013
 */

namespace Mmoreram\PaymillBundle\Router;
 
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Paymill router
 */
class PaymillRoutesLoader implements LoaderInterface
{

    /**
     * @var string
     * 
     * Execution route name
     */
    const ROUTE_NAME = 'paymill_execute';


    /**
     * @var string
     * 
     * Execution controller route
     */
    private $controllerRoute;


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
     */
    public function __construct($controllerRoute)
    {
        $this->controllerRoute = $controllerRoute;
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
        $routes->add(self::ROUTE_NAME , new Route($this->controllerRoute, array(
            '_controller'   =>  'PaymillBundle:Paymill:execute',
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
        return 'extra' === $type;
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