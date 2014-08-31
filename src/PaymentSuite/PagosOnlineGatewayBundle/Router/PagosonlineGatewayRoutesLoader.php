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

namespace PaymentSuite\PagosOnlineGatewayBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Dineromail router
 */
class PagosonlineGatewayRoutesLoader implements LoaderInterface
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
     * Execution controller route confirmation
     */
    private $controllerRouteConfirmation;

    /**
     * @var string
     *
     * Execution controller route confirmation name
     */
    private $controllerRouteConfirmationName;

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
     * @param $controllerRouteConfirmation Controller route
     * @param $controllerRouteConfirmationName Controller route name
     * @param $controllerRouteResponse Controller route
     * @param $controllerRouteResponseName Controller route name
     */
    public function __construct($controllerRoute, $controllerRouteName, $controllerRouteConfirmation, $controllerRouteConfirmationName, $controllerRouteResponse, $controllerRouteResponseName)
    {
        $this->controllerRoute = $controllerRoute;
        $this->controllerRouteName = $controllerRouteName;
        $this->controllerRouteConfirmation = $controllerRouteConfirmation;
        $this->controllerRouteConfirmationName = $controllerRouteConfirmationName;
        $this->controllerRouteResponse = $controllerRouteResponse;
        $this->controllerRouteResponseName = $controllerRouteResponseName;
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
                '_controller'   =>  'PagosonlineGatewayBundle:PagosonlineGateway:execute',
        )));

        $routes->add($this->controllerRouteConfirmationName, new Route($this->controllerRouteConfirmation, array(
            '_controller'   =>  'PagosonlineGatewayBundle:PagosonlineGateway:confirmation'
        ), array(), array(), '', array('http')));

        $routes->add($this->controllerRouteResponseName, new Route($this->controllerRouteResponse, array(
            '_controller'   =>  'PagosonlineGatewayBundle:PagosonlineGateway:response',
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
        return 'pagosonline_gateway' === $type;
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
