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

namespace PaymentSuite\GoogleWalletBundle\Twig;

use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\GoogleWalletBundle\Router\GoogleWalletRoutesLoader;
use PaymentSuite\GoogleWalletBundle\Services\GoogleWalletManager;

/**
 * Text utilities extension
 *
 */
class GoogleWalletExtension extends Twig_Extension
{
    /**
     * @var Twig_Environment
     *
     * Twig environment
     */
    private $environment;

    /**
     * @var GoogleWalletManager
     *
     */
    private $googlewalletManager;

    /**
     * Construct method
     *
     * @param GoogleWalletManager $googlewalletManager Form factory
     */
    public function __construct(GoogleWalletManager $googlewalletManager)
    {
        $this->googlewalletManager = $googlewalletManager;
    }

    /**
     * Init runtime
     *
     * @param Twig_Environment $environment Twig environment
     *
     * @return GoogleWalletExtension self object
     */
    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('googlewallet_render', array($this, 'renderPaymentView')),
            new Twig_SimpleFunction('googlewallet_scripts', array($this, 'renderPaymentScripts'))
        );
    }

    /**
     * Render googlewallet form view
     *
     * @return string view html
     */
    public function renderPaymentView()
    {
        return $this->environment->display('GoogleWalletBundle:GoogleWallet:view.html.twig', array(
            'gw_token'  =>  $this->googlewalletManager->generateToken(),
            'googlewallet_callback_route' =>  GoogleWalletRoutesLoader::ROUTE_CALLBACK   ,
        ));
    }

    /**
     * Render googlewallet scripts view
     *
     * @return string js code needed by GoogleWallet behaviour
     */
    public function renderPaymentScripts()
    {
        return $this->environment->display('GoogleWalletBundle:GoogleWallet:scripts.html.twig');
    }

    /**
     * return extension name
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_googlewallet_extension';
    }
}
