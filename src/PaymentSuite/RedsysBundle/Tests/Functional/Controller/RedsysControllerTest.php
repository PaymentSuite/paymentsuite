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

namespace PaymentSuite\RedsysBundle\Tests\Functional\Controller;

use Elcodi\Bundle\TestCommonBundle\Functional\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DefaultControllerTest
 */
class RedsysControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testExecute()
    {
        $crawler = $this
            ->client
            ->request('GET', '/payment/redsys/execute');

        $this->assertEquals(1, $crawler->filter('form')->count());

        $this->assertEquals(9, $crawler->filter('input[type="hidden"]')->count());
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = [])
    {
        static::$class = static::getKernelClass();

        $namespaceExploded = explode('\\Tests\\Functional\\', get_called_class(), 2);
        $bundleName = explode('PaymentSuite\\', $namespaceExploded[0], 2)[1];
        $bundleName = str_replace('\\', '_', $bundleName);

        return new static::$class($bundleName . 'Test', true);
    }
}
