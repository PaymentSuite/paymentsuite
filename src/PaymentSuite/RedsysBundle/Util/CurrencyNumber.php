<?php

namespace PaymentSuite\RedsysBundle\Util;

use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
final class CurrencyNumber
{
    const ISO_4217_CURRENCIES = [
        'EUR' => '978',
        'USD' => '840',
        'GBP' => '826',
        'JPY' => '392',
        'ARS' => '032',
        'CAD' => '124',
        'CLF' => '152',
        'COP' => '170',
        'INR' => '356',
        'MXN' => '484',
        'PEN' => '604',
        'CHF' => '756',
        'BRL' => '986',
        'VEF' => '937',
        'TRY' => '949',
    ];

    /**
     * @param string $code ISO 4217 currency code
     *
     * @return string ISO 4217 currency number
     *
     * @throws CurrencyNotSupportedException
     */
    public static function fromCode(string $code): string
    {
        if (!key_exists($code, self::ISO_4217_CURRENCIES)) {
            throw new CurrencyNotSupportedException();
        }

        return self::ISO_4217_CURRENCIES[$code];
    }
}
