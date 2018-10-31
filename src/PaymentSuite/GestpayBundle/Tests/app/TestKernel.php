<?php

namespace PaymentSuite\GestpayBundle\Tests\app;

use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use PaymentSuite\GestpayBundle\GestpayBundle;
use PaymentSuite\PaymentCoreBundle\PaymentCoreBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;

/**
 * Class TestKernel.
 */
class TestKernel extends HttpKernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new LiipFunctionalTestBundle(),
            new PaymentCoreBundle(),
            new GestpayBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/default.yml');
    }
}
