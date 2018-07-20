<?php

namespace PaymentSuite\RedsysBundle\Tests\app;

use PaymentSuite\PaymentCoreBundle\PaymentCoreBundle;
use PaymentSuite\RedsysBundle\RedsysBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class RedsysKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new PaymentCoreBundle(),
            new RedsysBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}
