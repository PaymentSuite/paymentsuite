<?php

namespace PaymentSuite\RedsysBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package PaymentSuite\RedsysBundle\Tests\Controller
 */
class RedsysControllerTest extends WebTestCase
{
    /**
     * Test execute controller
     */
    public function testExecute()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('form')->count() > 0);

        $this->assertTrue($crawler->filter('input[type="hidden"]')->count() > 9);

    }
}
