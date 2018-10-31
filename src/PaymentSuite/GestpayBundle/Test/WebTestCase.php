<?php

namespace PaymentSuite\GestpayBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use PaymentSuite\GestpayBundle\Tests\app\TestKernel;

/**
 * Class WebTestCase.
 */
class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}
