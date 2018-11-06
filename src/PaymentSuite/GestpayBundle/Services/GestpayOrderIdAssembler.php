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

namespace PaymentSuite\GestpayBundle\Services;

/**
 * Class GestpayOrderIdAssembler.
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayOrderIdAssembler
{
    const SEPARTOR = 'T';

    /**
     * @param int $orderId
     *
     * @return string
     */
    public static function assemble(int $orderId)
    {
        return sprintf('%d%s%d', $orderId, self::SEPARTOR, time());
    }

    /**
     * @param string $shopTransactionId
     *
     * @return int
     */
    public static function extract(string $shopTransactionId)
    {
        $pieces = explode(self::SEPARTOR, $shopTransactionId);

        return (int) $pieces[0];
    }
}
