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

namespace PaymentSuite\PaylandsBundle\Exception;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;

/**
 * Class InvalidValueException
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
class InvalidValueException extends \InvalidArgumentException
{
    public static function create(string $class, string $value): self
    {
        return new self(sprintf('%s is not a valid value for %s', $value, $class));
    }
}
