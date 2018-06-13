<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

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
    public static function getBundleDependencies(KernelInterface $kernel): array
    {
        return [
            'PaymentSuite\PaymentCoreBundle\PaymentCoreBundle',
        ];
    }
}
