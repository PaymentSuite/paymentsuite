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

namespace PaymentSuite\GestpayBundle\Tests\Unit\Services;

use EndelWar\GestPayWS\WSCryptDecrypt;
use PaymentSuite\GestpayBundle\Services\GestpayEncryptClientFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class GestpayEncryptClientFactoryTest
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class GestpayEncryptClientFactoryTest extends TestCase
{
    public function testCreateSandbox()
    {
        $client = GestpayEncryptClientFactory::create(true);
        $this->assertInstanceOf(WSCryptDecrypt::class, $client);
    }

    public function testCreateProduction()
    {
        $client = GestpayEncryptClientFactory::create();
        $this->assertInstanceOf(WSCryptDecrypt::class, $client);
    }
}
