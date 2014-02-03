<?php

namespace PaymentSuite\RedsysBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testExecute()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect('/redsys'));

        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('form')->count() > 0);

        $this->assertTrue($crawler->filter('input[type="text"]')->count() > 12);


    }
}
