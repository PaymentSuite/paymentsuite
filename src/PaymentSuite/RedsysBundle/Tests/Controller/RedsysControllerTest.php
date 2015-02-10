<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\RedsysBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class RedsysControllerTest extends WebTestCase
{
    /**
     * Test execute controller
     */
    public function testExecute()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/payment/redsys/execute');

        //$this->assertTrue($client->getResponse()->isRedirect());

        //$crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('form')->count());

        $this->assertEquals(9, $crawler->filter('input[type="hidden"]')->count());
    }
}