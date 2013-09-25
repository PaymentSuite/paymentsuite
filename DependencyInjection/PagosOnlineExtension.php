<?php

namespace Mmoreram\PaymillBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class PagosOnlineExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
    }
}