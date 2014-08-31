<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\BanwireGatewayBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Dineromail router
 */
class BanwireGatewayRoutesLoader implements LoaderInterface
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
     * Execution controller route response
     */
    private $controllerRouteResponse;

    /**
     * @var string
     *
     * Execution controller route  response name
     */
    private $controllerRouteResponseName;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

    /**
     * Construct method
     *
     * @param string $controllerRoute     Controller route
     * @param string $controllerRouteName Controller route name
     * @param $controllerRouteResponse Controller route
     * @param $controllerRouteResponseName Controller route name
     */
    public function __construct($controllerRoute, $controllerRouteName, $controllerRouteResponse, $controllerRouteResponseName)
    {
        $this->controllerRoute = $controllerRoute;
        $this->controllerRouteName = $controllerRouteName;
        $this->controllerRouteResponse = $controllerRouteResponse;
        $this->controllerRouteResponseName = $controllerRouteResponseName;
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
                '_controller'   =>  'BanwireGatewayBundle:BanwireGateway:execute',
        )));

        $routes->add($this->controllerRouteResponseName, new Route($this->controllerRouteResponse, array(
            '_controller'   =>  'BanwireGatewayBundle:BanwireGateway:response',
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
        return 'banwire_gateway' === $type;
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
