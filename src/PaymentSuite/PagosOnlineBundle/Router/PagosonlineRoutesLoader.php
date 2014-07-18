<?php

namespace PaymentSuite\PagosOnlineBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PagosonlineRoutesLoader implements LoaderInterface
{
    /**
     * @var string
     *
     * Execution route name
     */
   // const ROUTE_NAME = 'pagosonline_execute';

    /**
     * @var string
     *
     * Execution controller route
     */
    private $controllerRoute;

    /**
     * @var string
     *
     * Name controller route
     */
    private $controllerRouteName;

    /**
     * Construct method
     *
     * @param string $controllerRoute     Controller route
     * @param string $controllerRouteName
     */
    public function __construct($controllerRouteName, $controllerRoute, $container)
    {

        $this->controllerRouteName = $controllerRouteName;
        $this->controllerRoute = $controllerRoute;
    }

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

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
            '_controller'   =>  'PagosonlineBundle:Pagosonline:execute',
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
        return 'pagosonline' === $type;
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
