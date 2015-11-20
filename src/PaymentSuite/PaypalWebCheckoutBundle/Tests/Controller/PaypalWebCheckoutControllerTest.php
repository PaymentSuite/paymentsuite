<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PaypalWebCheckoutControllerTest
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 */
class PaypalWebCheckoutControllerTest extends WebTestCase
{
    public function testExecute()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/payment/paypal_web_checkout/execute');

        // $this->assertTrue($client->getResponse()->isRedirect());

        // $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('form#paypal_checkout_form')->count());

        $this->assertEquals(12, $crawler->filter('input[type="hidden"]')->count());
    }
}
