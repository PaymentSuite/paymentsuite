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

namespace PaymentSuite\PaymillBundle\Router;

use RuntimeException;
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
    private $controllerRouteName;

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
     * @param string $controllerRouteName Controller route name
     * @param string $controllerRoute     Controller route
     */
    public function __construct($controllerRouteName, $controllerRoute)
    {
        $this->controllerRouteName = $controllerRouteName;
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
        $routes->add($this->controllerRouteName, new Route($this->controllerRoute, array(
            '_controller'   =>  'PaymillBundle:Paymill:execute',
        ), array(), array(), '', array(), array(
            'POST'
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
        return 'paymill' === $type;
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
