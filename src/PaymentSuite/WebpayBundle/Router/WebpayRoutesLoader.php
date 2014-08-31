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

namespace PaymentSuite\WebpayBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Webpay router
 */
class WebpayRoutesLoader implements LoaderInterface
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
     * @var string
     *
     * Confirmation route name
     */
    protected $confirmationRouteName;

    /**
     * @var string
     *
     * Confirmation route schemes
     */
    protected $confirmationRouteSchemes;

    /**
     * @var string
     *
     * Confirmation controller route
     */
    protected $confirmationRoute;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    protected $loaded = false;

    /**
     * Construct method
     *
     * @param string $executeRouteName         Controller Execute route name
     * @param string $executeRouteSchemes      Controller Execute route schemes
     * @param string $executeRoute             Controller Execute route
     * @param string $confirmationRouteName    Controller Confirmation route name
     * @param string $confirmationRouteSchemes Controller Confirmation route schemes
     * @param string $confirmationRoute        Controller Confirmation route
     */
    public function __construct($executeRouteName, $executeRouteSchemes, $executeRoute, $confirmationRouteName, $confirmationRouteSchemes, $confirmationRoute)
    {
        $this->executeRouteName = $executeRouteName;
        $this->executeRouteSchemes = $executeRouteSchemes;
        $this->executeRoute = $executeRoute;
        $this->confirmationRouteName = $confirmationRouteName;
        $this->confirmationRouteSchemes = $confirmationRouteSchemes;
        $this->confirmationRoute = $confirmationRoute;
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
        $routes->add($this->executeRouteName, new Route(
            $this->executeRoute,
            array('_controller' => 'WebpayBundle:Webpay:execute'),
            array(),
            array(),
            '',
            $this->executeRouteSchemes));
        $routes->add($this->confirmationRouteName, new Route(
            $this->confirmationRoute,
            array('_controller' => 'WebpayBundle:Webpay:confirmation'),
            array(),
            array(),
            '',
            $this->confirmationRouteSchemes));
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
        return 'webpay' === $type;
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
