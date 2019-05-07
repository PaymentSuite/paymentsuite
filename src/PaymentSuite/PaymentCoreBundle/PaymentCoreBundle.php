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

namespace PaymentSuite\PaymentCoreBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use PaymentSuite\PaymentCoreBundle\DependencyInjection\PaymentCoreExtension;

/**
 * Core Payment Bundle.
 */
class PaymentCoreBundle extends Bundle
{
    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface The container extension
     */
    public function getContainerExtension()
    {
        return new PaymentCoreExtension();
    }
}
