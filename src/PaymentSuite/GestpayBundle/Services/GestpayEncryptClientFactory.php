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

use EndelWar\GestPayWS\WSCryptDecrypt;
use EndelWar\GestPayWS\WSCryptDecryptSoapClient;

/**
 * Class GestpayEncryptClientFactory.
 */
class GestpayEncryptClientFactory
{
    /**
     * @return WSCryptDecrypt
     */
    public static function create(bool $sandbox)
    {
        $soapClient = new WSCryptDecryptSoapClient($sandbox);

        return new WSCryptDecrypt($soapClient->getSoapClient());
    }
}
