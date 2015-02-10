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

namespace PaymentSuite\RedsysBundle\Services;

use Symfony\Component\Routing\RouterInterface;

class UrlFactory
{
    /**
     * @var RouterInterface
     *
     * Router instance
     */
    private $router;

    /**
     * @var string
     *
     * Route success
     */
    private $successRouteName;

    /**
     * @var string
     *
     * Route fail
     */
    private $failRouteName;

    /**
     * @var string
     *
     * Route result
     */
    private $resultRouteName;


    /**
     * @param RouterInterface $router                         Router instance
     * @param string          $successRouteName               Route name for a succesful payment return from Redsys
     * @param string          $failRouteName                  Route name for a cancelled payment from Redsys
     * @param string          $resultRouteName                Route name for a result Redsys
     */
    public function __construct(
        RouterInterface $router,
        $successRouteName,
        $failRouteName,
        $resultRouteName)
    {

        $this->router           = $router;
        $this->successRouteName = $successRouteName;
        $this->failRouteName    = $failRouteName;
        $this->resultRouteName  = $resultRouteName;
    }

    /**
     * Get the route succesful payment return from Redsys
     *
     * @param $orderId
     * @return string
     */
    public function getReturnUrlOkForOrderId($orderId)
    {
        return $this->router->generate(
            $this->successRouteName,
            array('id' => $orderId),
            true
        );
    }

    /**
     * Get the route cancelled payment from Redsys
     *
     * @param $orderId
     * @return string
     */
    public function getReturnUrlKoForOrderId($orderId)
    {
        return $this->router->generate(
            $this->failRouteName,
            array('id' => $orderId),
            true
        );
    }

    /**
     * Get the route result Redsys
     *
     * @return string
     */
    public function getReturnRedsysUrl()
    {
        return $this->router->generate(
            $this->resultRouteName,
            array(),
            true);
    }
}
