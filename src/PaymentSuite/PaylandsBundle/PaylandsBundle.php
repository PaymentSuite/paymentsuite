<?php

namespace PaymentSuite\PaylandsBundle;

use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;
use PaymentSuite\PaylandsBundle\DependencyInjection\PaylandsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class PaylandsBundle.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsBundle extends Bundle implements DependentBundleInterface
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensionClass()
    {
        return PaylandsExtension::class;
    }

    /**
     * {@inheritdoc}
     */
    public static function getBundleDependencies(KernelInterface $kernel)
    {
        return [
            'PaymentSuite\PaymentCoreBundle\PaymentCoreBundle',
        ];
    }
}
