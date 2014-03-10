<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package WebpayBundle
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
    private $executeRouteName;

    /**
     * @var string
     *
     * Execution controller route
     */
    private $executeRoute;

    /**
     * @var string
     *
     * Confirmation route name
     */
    private $confirmationRouteName;

    /**
     * @var string
     *
     * Confirmation controller route
     */
    private $confirmationRoute;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

    /**
     * Construct method
     *
     * @param string $executeRouteName      Controller Execute route name
     * @param string $executeRoute          Controller Execute route
     * @param string $confirmationRouteName Controller Confirmation route name
     * @param string $confirmationRoute     Controller Confirmation route
     */
    public function __construct($executeRouteName, $executeRoute, $confirmationRouteName, $confirmationRoute)
    {
        $this->executeRouteName = $executeRouteName;
        $this->executeRoute = $executeRoute;
        $this->confirmationRouteName = $confirmationRouteName;
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
        $routes->add($this->executeRouteName, new Route($this->executeRoute, array('_controller' => 'WebpayBundle:Webpay:execute')));
        $routes->add($this->confirmationRouteName, new Route($this->confirmationRoute, array('_controller' => 'WebpayBundle:Webpay:confirmation')));
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
